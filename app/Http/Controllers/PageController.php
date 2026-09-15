<?php
namespace App\Http\Controllers;
use App\Models\Page;
use Illuminate\Support\Facades\Schema;

class PageController extends Controller
{
    public function show(string $slug)
    {
        abort_unless(Schema::hasTable((new Page())->getTable()), 404);

        $page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('froentend.pages.show', [
            'page' => $page,
            'meta_title' => $page->meta_title ?: $page->title . ' | Vayu',
            'meta_description' => $page->meta_description ?: str($page->content)->stripTags()->limit(160),
            'canonical' => route('pages.show', $page->slug),
        ]);
    }
}
