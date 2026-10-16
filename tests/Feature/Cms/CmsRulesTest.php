<?php

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\User;
use App\Support\MenuResolver;
use App\Support\PageSections;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

it('normalizes sections and drops unknown types instead of failing the page', function () {
    $normalized = PageSections::normalize([
        'version' => 1,
        'sections' => [
            ['id' => 'hero-1', 'type' => 'hero', 'data' => ['title' => 'Halo']],
            ['id' => 'x-1', 'type' => 'unknown_widget', 'data' => []],
        ],
    ]);

    expect($normalized['version'])->toBe(1)
        ->and($normalized['sections'])->toHaveCount(1)
        ->and($normalized['sections'][0]['type'])->toBe('hero');
});

it('sanitizes custom html sections', function () {
    $normalized = PageSections::normalize([
        ['id' => 'c-1', 'type' => 'custom_html', 'data' => ['html' => '<p>OK</p><script>alert(1)</script><a href="javascript:alert(1)">x</a>']],
    ]);

    expect($normalized['sections'][0]['data']['html'])->not->toContain('<script')->not->toContain('javascript:');
});

it('maps stored sections to builder state and back without losing ids', function () {
    $stored = [
        'version' => 1,
        'sections' => [
            ['id' => 'hero', 'type' => 'hero', 'data' => ['eyebrow' => 'SMA', 'title' => 'Halo', 'cta' => ['label' => 'Daftar', 'url' => '/spmb']]],
            ['id' => 'nilai', 'type' => 'values', 'data' => ['eyebrow' => 'Karakter', 'title' => 'Nilai', 'items' => [['icon' => 'heart', 'title' => 'Adab']]]],
        ],
    ];

    $builder = PageSections::builderStateFromStored($stored);

    expect($builder)->toHaveCount(2)
        ->and($builder[0]['type'])->toBe('hero')
        ->and($builder[0]['data']['title'])->toBe('Halo');

    // Simulasi edit admin: ubah title, tambah item baru.
    $builder[0]['data']['title'] = 'Halo Baru';
    $builder[] = ['type' => 'cta', 'data' => ['title' => 'Join']];

    $saved = PageSections::storedFromBuilderState($builder, $stored);

    expect($saved['version'])->toBe(1)
        ->and($saved['sections'])->toHaveCount(3)
        ->and($saved['sections'][0]['id'])->toBe('hero')
        ->and($saved['sections'][0]['data']['title'])->toBe('Halo Baru')
        ->and($saved['sections'][0]['data']['eyebrow'])->toBe('SMA')
        ->and($saved['sections'][0]['data']['cta'])->toBe(['label' => 'Daftar', 'url' => '/spmb'])
        ->and($saved['sections'][1]['id'])->toBe('nilai')
        ->and($saved['sections'][1]['data']['items'])->toBe([['icon' => 'heart', 'title' => 'Adab']])
        ->and($saved['sections'][2]['id'])->toBe('cta-3');
});

it('normalizes section record lists for repeater fields', function () {
    $normalized = PageSections::normalize([
        [
            'id' => 'nilai',
            'type' => 'values',
            'data' => [
                'items' => json_encode([
                    ['icon' => 'heart', 'title' => 'Adab', 'description' => 'Akhlak mulia'],
                ]),
            ],
        ],
        [
            'id' => 'faq',
            'type' => 'faq',
            'data' => ['items' => "Kapan dibuka?: Awal tahun\nApa jurusannya?: MIPA"],
        ],
    ]);

    expect($normalized['sections'][0]['data']['items'])->toBe([
        ['icon' => 'heart', 'title' => 'Adab', 'description' => 'Akhlak mulia'],
    ])
        ->and($normalized['sections'][1]['data']['items'])->toBe([
            ['q' => 'Kapan dibuka?', 'a' => 'Awal tahun'],
            ['q' => 'Apa jurusannya?', 'a' => 'MIPA'],
        ]);
});

it('reindexes repeater record lists keyed by uuid', function () {
    $stored = [
        'version' => 1,
        'sections' => [
            ['id' => 'statistik', 'type' => 'statistics', 'data' => ['items' => [['label' => 'Siswa', 'value' => '100']]]],
        ],
    ];

    $builder = [
        [
            'type' => 'statistics',
            'data' => [
                'items' => [
                    '9dd4e461-2e1f-4f2f-9d2b-6ad1c4b1a0a1' => ['label' => 'Guru', 'value' => '20'],
                    '9dd4e461-2e1f-4f2f-9d2b-6ad1c4b1a0a2' => ['label' => 'Siswa', 'value' => '200'],
                ],
            ],
        ],
    ];

    $saved = PageSections::storedFromBuilderState($builder, $stored);

    expect($saved['sections'][0]['data']['items'])->toBe([
        ['label' => 'Guru', 'value' => '20'],
        ['label' => 'Siswa', 'value' => '200'],
    ]);
});

it('enforces unique slugs', function () {
    Page::factory()->create(['slug' => 'unik-test']);

    expect(fn () => Page::factory()->create(['slug' => 'unik-test']))->toThrow(QueryException::class);
});

it('prevents menu parent cycles', function () {
    $menu = Menu::create(['name' => 'Header', 'location' => 'cycle-test']);
    $parent = MenuItem::create(['menu_id' => $menu->id, 'label' => 'Parent', 'type' => 'dropdown', 'target' => '_self', 'is_visible' => true, 'sort_order' => 1]);
    $child = MenuItem::create(['menu_id' => $menu->id, 'label' => 'Child', 'type' => 'dropdown', 'parent_id' => $parent->id, 'target' => '_self', 'is_visible' => true, 'sort_order' => 1]);

    expect(MenuResolver::wouldCreateCycle($parent->id, $child->id))->toBeTrue();

    $parent->parent_id = $child->id;
    expect(fn () => $parent->save())->toThrow(ValidationException::class);
});

it('opens the page edit form for wrapped builder sections', function () {
    $user = User::factory()->create();
    Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
    $user->assignRole('editor');

    $page = Page::factory()->create([
        'sections' => [
            'version' => 1,
            'sections' => [
                ['id' => 'hero', 'type' => 'hero', 'data' => ['title' => 'Halo', 'cta' => ['label' => 'Daftar', 'url' => '/spmb']]],
                ['id' => 'nilai', 'type' => 'values', 'data' => ['title' => 'Nilai', 'items' => [['icon' => 'heart', 'title' => 'Adab']]]],
                ['id' => 'statistik', 'type' => 'statistics', 'data' => ['items' => [['label' => 'Siswa', 'value' => '100']]]],
                ['id' => 'pengurus', 'type' => 'card_grid', 'data' => ['title' => 'Pengurus', 'cards' => [['icon' => 'users', 'title' => 'Ketua', 'description' => 'Memimpin']]]],
                ['id' => 'faq', 'type' => 'faq', 'data' => ['items' => json_encode([['q' => 'Kapan?', 'a' => '<p>Awal tahun</p>']])]],
            ],
        ],
    ]);

    $this->actingAs($user)->get("/admin/pages/{$page->id}/edit")->assertOk();
});

it('protects admin routes and role boundaries', function () {
    $this->get('/admin')->assertRedirect('/admin/login');

    $user = User::factory()->create();
    $this->assertFalse($user->cannotElevateSelf(['viewer']));
    $this->assertTrue($user->cannotElevateSelf(['super_admin']));
});
