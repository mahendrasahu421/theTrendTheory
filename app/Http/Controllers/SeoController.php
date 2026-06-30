<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $urls = collect([
            url('/'),
            route('shop.index'),
            route('shop.new-arrivals'),
        ]);

        $urls = $urls
            ->merge(Category::where('is_active', true)->pluck('slug')->map(fn ($slug) => route('shop.category', $slug)))
            ->merge(Product::where('is_active', true)->pluck('slug')->map(fn ($slug) => route('product.show', $slug)));

        $xml = view('sitemap', ['urls' => $urls])->render();

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function robots(): Response
    {
        $content = "User-agent: *\nAllow: /\nSitemap: ".url('/sitemap.xml')."\n";

        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}
