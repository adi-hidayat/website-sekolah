<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\News;
use App\Models\Page;
use Illuminate\Http\Response as HttpResponse;

class SitemapController extends Controller
{
    public function __invoke(): HttpResponse
    {
        $urls = collect(['', '/jurusan', '/berita', '/pengumuman']);

        Page::published()->pluck('slug')->each(fn ($s) => $urls->push('/'.$s));
        News::published()->pluck('slug')->each(fn ($s) => $urls->push('/berita/'.$s));
        Department::published()->pluck('slug')->each(fn ($s) => $urls->push('/jurusan/'.$s));

        $xml = view('sitemap', ['urls' => $urls->unique()->values()])->render();

        return response($xml, 200)->header('Content-Type', 'text/xml');
    }
}
