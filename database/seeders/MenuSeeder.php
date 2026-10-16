<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Menu navigasi header dan footer (PRD §5.2, §5.14).
     */
    public function run(): void
    {
        $header = Menu::updateOrCreate(['location' => 'header'], ['name' => 'Header', 'description' => 'Navigasi utama situs.']);
        $footer = Menu::updateOrCreate(['location' => 'footer'], ['name' => 'Footer', 'description' => 'Navigasi pada bagian footer.']);

        $this->seedHeader($header);
        $this->seedFooter($footer);
    }

    private function seedHeader(Menu $menu): void
    {
        $this->item($menu, 'Beranda', ['type' => 'custom_url', 'target_url' => '/'], null, 1);

        $profil = $this->item($menu, 'Profil', ['type' => 'dropdown'], null, 2);
        $this->item($menu, 'Profil Sekolah', ['type' => 'page', 'page_id' => $this->pageId('profil')], $profil->id, 1);
        $this->item($menu, 'Visi dan Misi', ['type' => 'page', 'page_id' => $this->pageId('visi-misi')], $profil->id, 2);
        $this->item($menu, 'Struktur Organisasi', ['type' => 'page', 'page_id' => $this->pageId('struktur-organisasi')], $profil->id, 3);

        $akademik = $this->item($menu, 'Akademik', ['type' => 'dropdown'], null, 3);
        $this->item($menu, 'Program Jurusan', ['type' => 'custom_url', 'target_url' => '/jurusan'], $akademik->id, 1);
        $this->item($menu, 'Akademik', ['type' => 'page', 'page_id' => $this->pageId('akademik')], $akademik->id, 2);
        $this->item($menu, 'Ekstrakurikuler', ['type' => 'page', 'page_id' => $this->pageId('ekstrakurikuler')], $akademik->id, 3);
        $this->item($menu, 'Fasilitas', ['type' => 'page', 'page_id' => $this->pageId('fasilitas')], $akademik->id, 4);

        $jurusan = $this->item($menu, 'Jurusan', ['type' => 'dropdown'], null, 4);
        $departmentIds = [];
        foreach (Department::published()->orderBy('sort_order')->get() as $index => $department) {
            // Kunci by department_id (bukan label) agar rename jurusan
            // tidak menciptakan baris ganda saat seed dijalankan ulang.
            MenuItem::updateOrCreate(
                ['menu_id' => $menu->id, 'parent_id' => $jurusan->id, 'type' => 'department', 'department_id' => $department->id],
                ['label' => $department->name, 'target' => '_self', 'is_visible' => true, 'sort_order' => $index + 1],
            );
            $departmentIds[] = $department->id;
        }
        MenuItem::where('menu_id', $menu->id)
            ->where('parent_id', $jurusan->id)
            ->where('type', 'department')
            ->whereNotIn('department_id', $departmentIds)
            ->delete();

        $this->item($menu, 'Berita', ['type' => 'news'], null, 5);
        $this->item($menu, 'Galeri', ['type' => 'gallery'], null, 6);

        $informasi = $this->item($menu, 'Informasi', ['type' => 'dropdown'], null, 7);
        $this->item($menu, 'Pengumuman', ['type' => 'announcement'], $informasi->id, 1);
        $this->item($menu, 'SPMB', ['type' => 'page', 'page_id' => $this->pageId('spmb')], $informasi->id, 2);
        $this->item($menu, 'FAQ', ['type' => 'page', 'page_id' => $this->pageId('faq')], $informasi->id, 3);
        $this->item($menu, 'Kontak', ['type' => 'page', 'page_id' => $this->pageId('kontak')], $informasi->id, 4);
    }

    private function seedFooter(Menu $menu): void
    {
        $links = [
            ['Profil Sekolah', 'page', 'profil'],
            ['Visi dan Misi', 'page', 'visi-misi'],
            ['Program Jurusan', 'custom_url', null],
            ['Berita', 'news', null],
            ['Pengumuman', 'announcement', null],
            ['Galeri', 'gallery', null],
            ['Fasilitas', 'page', 'fasilitas'],
            ['Prestasi', 'page', 'prestasi'],
            ['Informasi SPMB', 'page', 'spmb'],
            ['Kontak', 'page', 'kontak'],
            ['Kebijakan Privasi', 'page', 'kebijakan-privasi'],
        ];

        foreach ($links as $index => [$label, $type, $slug]) {
            $attributes = ['type' => $type];

            if ($type === 'page') {
                $attributes['page_id'] = $this->pageId($slug);
            }

            if ($type === 'custom_url') {
                $attributes['target_url'] = '/jurusan';
            }

            $this->item($menu, $label, $attributes, null, $index + 1);
        }
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function item(Menu $menu, string $label, array $attributes, ?int $parentId = null, int $order = 1): MenuItem
    {
        return MenuItem::updateOrCreate(
            ['menu_id' => $menu->id, 'parent_id' => $parentId, 'label' => $label],
            array_merge([
                'type' => 'custom_url',
                'target' => '_self',
                'is_visible' => true,
                'sort_order' => $order,
            ], $attributes),
        );
    }

    private function pageId(string $slug): ?int
    {
        return Page::where('slug', $slug)->value('id');
    }
}
