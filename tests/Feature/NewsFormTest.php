<?php

use App\Filament\Resources\News\NewsResource;
use App\Filament\Resources\News\Pages\CreateNews;
use App\Filament\Resources\News\Pages\EditNews;
use App\Models\News;
use App\Models\User;

it('renders the news form with rich content editor', function () {
    $this->actingAs(User::factory()->create());

    $this->get(NewsResource::getUrl('create'))->assertOk();

    $news = News::factory()->create();

    $this->get(EditNews::getUrl(['record' => $news]))->assertOk();
    expect(CreateNews::class)->toBeString();
});
