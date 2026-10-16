<?php

namespace Database\Seeders\Concerns;

trait ProvidesPlaceholderImages
{
    /**
     * URL gambar placeholder deterministik untuk data contoh.
     */
    protected function photo(string $seed, int $width = 1200, int $height = 800): string
    {
        return sprintf(
            'https://picsum.photos/seed/%s/%d/%d',
            rawurlencode($seed),
            $width,
            $height,
        );
    }
}
