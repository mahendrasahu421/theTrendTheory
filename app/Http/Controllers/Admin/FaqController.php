<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        return view('admin.faqs.index');
    }

    public function ajax(Request $request)
    {
        $perPage  = (int) $request->get('per_page', 10);
        $page     = (int) $request->get('page', 1);
        $search   = trim($request->get('search', ''));
        $category = $request->get('category', '');
        $status   = $request->get('status', '');

        $q = Faq::query();
        if ($search)   $q->where('question', 'like', '%'.$search.'%');
        if ($category) $q->where('category', $category);
        if ($status !== '') $q->where('is_active', (bool)(int)$status);
        $q->orderBy('sort_order')->orderBy('category');

        $result = $q->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $result->map(fn($f) => [
                'id'        => $f->id,
                'question'  => $f->question,
                'answer'    => $f->answer,
                'category'  => $f->category,
                'is_active' => (bool) $f->is_active,
                'sort_order'=> $f->sort_order,
            ]),
            'total'        => $result->total(),
            'per_page'     => $result->perPage(),
            'current_page' => $result->currentPage(),
            'last_page'    => $result->lastPage(),
            'from'         => $result->firstItem() ?? 0,
            'to'           => $result->lastItem() ?? 0,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:500',
            'answer'   => 'required|string',
            'category' => 'required|in:general,shipping,returns,product',
        ]);

        Faq::create([
            'question'   => $request->question,
            'answer'     => $request->answer,
            'category'   => $request->category,
            'is_active'  => $request->boolean('is_active', true),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'question' => 'required|string|max:500',
            'answer'   => 'required|string',
            'category' => 'required|in:general,shipping,returns,product',
        ]);

        $faq->update([
            'question'   => $request->question,
            'answer'     => $request->answer,
            'category'   => $request->category,
            'is_active'  => $request->boolean('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return response()->json(['success' => true]);
    }

    public function toggle(Faq $faq)
    {
        $faq->update(['is_active' => !$faq->is_active]);
        return response()->json(['success' => true]);
    }
}