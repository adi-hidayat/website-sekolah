<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @php
            $siteGeneral = [];
            $siteThemeVars = '';
            $siteFontUrl = null;
            try {
                $siteGeneral = \App\Support\SitePresentation::general();
                $siteThemeVars = \App\Support\SitePresentation::inlineStyle();
                $siteFontUrl = \App\Support\SitePresentation::fontUrl();
            } catch (\Throwable) {
                $siteGeneral = [];
            }
            $siteTitle = $siteGeneral['seo_title'] ?? $siteGeneral['site_name'] ?? $siteGeneral['school_name'] ?? config('app.name', 'Laravel');
            $siteDescription = $siteGeneral['seo_description'] ?? null;
            $siteKeywords = $siteGeneral['seo_keywords'] ?? null;
            $siteFavicon = \App\Support\SitePresentation::logoUrl($siteGeneral['favicon'] ?? null);
            $siteOgImage = \App\Support\SitePresentation::logoUrl($siteGeneral['og_image'] ?? $siteGeneral['logo'] ?? null);
        @endphp

        @if ($siteFavicon)
            <link rel="icon" href="{{ $siteFavicon }}">
        @else
            <link rel="icon" href="/favicon.ico" sizes="any">
            <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        @endif
        <link rel="apple-touch-icon" href="{{ $siteFavicon ?? '/apple-touch-icon.png' }}">

        @if ($siteDescription)
            <meta name="description" content="{{ $siteDescription }}">
        @endif
        @if ($siteKeywords)
            <meta name="keywords" content="{{ $siteKeywords }}">
        @endif
        @if ($siteOgImage)
            <meta property="og:image" content="{{ $siteOgImage }}">
        @endif
        <meta property="og:title" content="{{ $siteTitle }}">
        @if ($siteDescription)
            <meta property="og:description" content="{{ $siteDescription }}">
        @endif
        <meta property="og:type" content="website">

        @if ($siteFontUrl)
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link rel="stylesheet" href="{{ $siteFontUrl }}">
        @endif

        @if ($siteThemeVars !== '')
            <style>:root { {{ $siteThemeVars }} }</style>
        @endif

        @fonts

        @vite(['resources/css/app.css', 'resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        <x-inertia::head>
            <title>{{ $siteTitle }}</title>
        </x-inertia::head>
    </head>
    <body class="font-sans antialiased">
        <x-inertia::app />
    </body>
</html>
