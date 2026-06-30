<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('title')->get();
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:200',
            'content' => 'required|string',
        ]);

        $slug = Str::slug($request->title);
        $orig = $slug; $n = 1;
        while (Page::where('slug', $slug)->exists()) {
            $slug = $orig.'-'.$n++;
        }

        Page::create([
            'title'            => $request->title,
            'slug'             => $slug,
            'content'          => $request->content,
            'meta_title'       => $request->meta_title,
            'meta_description' => $request->meta_description,
            'is_active'        => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page "'.$request->title.'" created!');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.create', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title'   => 'required|string|max:200',
            'content' => 'required|string',
        ]);

        $page->update([
            'title'            => $request->title,
            'content'          => $request->content,
            'meta_title'       => $request->meta_title,
            'meta_description' => $request->meta_description,
            'is_active'        => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page updated!');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return back()->with('success', 'Page deleted!');
    }
}