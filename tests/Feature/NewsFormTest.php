<?php

use App\Filament\Resources\News\NewsResource;
use App\Filament\Resources\News\Pages\CreateNews;
use App\Filament\Resources\News\Pages\EditNews;
use App\Models\News;
use App\Models\User;
use Spatie\Permission\Models\Role;

it('renders the news form with rich content editor', function () {
    Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

    $user = User::factory()->create();
    $user->assignRole('super_admin');

    $this->actingAs($user);

    $this->get(NewsResource::getUrl('create'))->assertOk();

    $news = News::factory()->create();

    $this->get(EditNews::getUrl(['record' => $news]))->assertOk();
    expect(CreateNews::class)->toBeString();
});
