<?php

namespace App\Support;

use App\Enums\MenuItemType;
use App\Models\MenuItem;

/**
 * Membangun tree menu yang aman untuk frontend.
 * Item tidak valid tidak boleh merusak seluruh navigasi (PRD 6.5).
 */
class MenuResolver
{
    /**
     * @param  iterable<int, MenuItem>  $items
     * @return array<int, array<string, mixed>>
     */
    public static function tree(iterable $items, int $maxDepth = 3): array
    {
        $byParent = [];
        foreach ($items as $item) {
            if (! $item->is_visible) {
                continue;
            }
            $byParent[$item->parent_id ?? 0][] = $item;
        }

        $build = function (int $parentId, int $depth) use (&$build, $byParent, $maxDepth): array {
            $nodes = [];
            foreach ($byParent[$parentId] ?? [] as $item) {
                try {
                    $url = $item->resolvedUrl();
                } catch (\Throwable) {
                    continue;
                }

                // Menu ke halaman draft / terhapus tidak tampil sebagai tautan aktif.
                if ($url === null && $item->type !== MenuItemType::Dropdown) {
                    continue;
                }

                $children = $depth < $maxDepth ? $build((int) $item->id, $depth + 1) : [];

                $nodes[] = [
                    'id' => $item->id,
                    'label' => $item->label,
                    'type' => $item->type->value,
                    'url' => $url,
                    'target' => $item->target,
                    'children' => $children,
                ];
            }

            return $nodes;
        };

        return $build(0, 1);
    }

    /**
     * Cegah parent loop: pastikan $parentId bukan diri sendiri maupun descendant.
     */
    public static function wouldCreateCycle(?int $itemId, ?int $parentId): bool
    {
        if ($parentId === null || $itemId === null) {
            return false;
        }

        if ($parentId === $itemId) {
            return true;
        }

        $seen = [$itemId];
        $current = MenuItem::query()->find($parentId);
        $guard = 0;

        while ($current && $guard++ < 50) {
            if (in_array($current->parent_id, $seen, true)) {
                return true;
            }
            if ($current->parent_id === null) {
                return false;
            }
            $seen[] = $current->parent_id;
            $current = MenuItem::query()->find($current->parent_id);
        }

        return false;
    }
}
