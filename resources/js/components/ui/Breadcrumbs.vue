<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppIcon from '@/components/ui/AppIcon.vue';

/**
 * Breadcrumb untuk halaman detail (PRD 6 & 11). Item terakhir tidak
 * ditautkan dan ditandai aria-current.
 */
withDefaults(
    defineProps<{
        items: Array<{ label: string; href?: string | null }>;
    }>(),
    {
        items: () => [],
    },
);
</script>

<template>
    <nav aria-label="Breadcrumb" class="w-full">
        <ol class="flex flex-wrap items-center gap-1.5 text-sm text-muted">
            <li
                v-for="(item, index) in items"
                :key="`${item.label}-${index}`"
                class="flex items-center gap-1.5"
            >
                <AppIcon
                    v-if="index > 0"
                    name="chevron-right"
                    :size="14"
                    class="text-border"
                />
                <Link
                    v-if="item.href && index < items.length - 1"
                    :href="item.href"
                    class="rounded-sm transition-colors hover:text-primary focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                >
                    {{ item.label }}
                </Link>
                <span
                    v-else
                    :aria-current="index === items.length - 1 ? 'page' : undefined"
                    :class="
                        index === items.length - 1
                            ? 'font-medium text-foreground'
                            : ''
                    "
                >
                    {{ item.label }}
                </span>
            </li>
        </ol>
    </nav>
</template>
