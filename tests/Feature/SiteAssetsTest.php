<?php

use App\Support\SitePresentation;
use Illuminate\Support\Facades\Storage;

it('maps stored setting files to public urls', function () {
    expect(SitePresentation::logoUrl('settings/logo.png'))->toBe('/storage/settings/logo.png')
        ->and(SitePresentation::logoUrl('/storage/settings/logo.png'))->toBe('/storage/settings/logo.png')
        ->and(SitePresentation::logoUrl('https://example.com/logo.png'))->toBe('https://example.com/logo.png')
        ->and(SitePresentation::logoUrl(null))->toBeNull()
        ->and(SitePresentation::logoUrl(''))->toBeNull();
});

it('exposes uploaded setting files through the public storage link', function () {
    Storage::disk('public')->put('settings/test-logo.png', 'fake-image-bytes');

    try {
        // File yang disimpan via disk public harus bisa dijangkau web
        // lewat symlink public/storage.
        expect(file_exists(public_path('storage/settings/test-logo.png')))->toBeTrue();
    } finally {
        Storage::disk('public')->delete('settings/test-logo.png');
    }
});
