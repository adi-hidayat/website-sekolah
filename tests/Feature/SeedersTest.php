<?php

use App\Models\Achievement;
use App\Models\Announcement;
use App\Models\Department;
use App\Models\Facility;
use App\Models\Gallery;
use App\Models\GalleryItem;
use App\Models\MenuItem;
use App\Models\News;
use App\Models\Page;
use App\Models\SchoolSetting;
use App\Models\Teacher;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Hash;

it('seeds a rich dataset across all content types', function () {
    $this->seed(DatabaseSeeder::class);

    expect(Department::published()->count())->toBeGreaterThanOrEqual(6)
        ->and(Teacher::count())->toBeGreaterThanOrEqual(15)
        ->and(News::published()->count())->toBeGreaterThanOrEqual(20)
        ->and(Announcement::active()->count())->toBeGreaterThanOrEqual(10)
        ->and(Achievement::published()->count())->toBeGreaterThanOrEqual(20)
        ->and(Facility::active()->count())->toBeGreaterThanOrEqual(8)
        ->and(Gallery::published()->count())->toBeGreaterThanOrEqual(6)
        ->and(GalleryItem::count())->toBeGreaterThanOrEqual(30)
        ->and(MenuItem::count())->toBeGreaterThanOrEqual(20)
        ->and(Page::published()->count())->toBeGreaterThanOrEqual(10);

    expect(SchoolSetting::current()->school_name)->not->toBeEmpty();
});

it('creates the requested admin account with working credentials', function () {
    $this->seed(DatabaseSeeder::class);

    $admin = User::where('email', 'admin@example.com')->first();

    expect($admin)->not->toBeNull()
        ->and(Hash::check('12345678', $admin->password))->toBeTrue();
});

it('renders key public pages from the seeded content', function () {
    $this->seed(DatabaseSeeder::class);

    $this->get(route('home'))->assertOk();
    $this->get(route('news.index'))->assertOk();
    $this->get(route('departments.index'))->assertOk();
    $this->get(route('announcements.index'))->assertOk();
    $this->get('/profil')->assertOk();
    $this->get('/spmb')->assertOk();
});
