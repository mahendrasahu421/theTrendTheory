<?php
// app/Http/Controllers/Admin/SizeController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index() { return view('admin.variants.sizes'); }

    public function ajax(Request $request)
    {
        try {
            $perPage  = (int) $request->get('per_page', 25);
            $page     = (int) $request->get('page', 1);
            $search   = $request->get('search', '');
            $category = $request->get('category', '');
            $status   = $request->get('status', '');

            $query = Size::withCount('variants');

            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('display_name', 'LIKE', "%{$search}%");
                });
            }
            if (!empty($category)) $query->where('category', $category);
            if ($status !== '')    $query->where('is_active', $status == '1');

            $query->orderBy('category')->orderBy('sort_order')->orderBy('name');
            $sizes = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'data'         => $sizes->map(fn($s) => [
                    'id'            => $s->id,
                    'name'          => $s->name,
                    'display_name'  => $s->display_name,
                    'category'      => $s->category,
                    'sort_order'    => $s->sort_order,
                    'is_active'     => (bool) $s->is_active,
                    'variants_count'=> $s->variants_count,
                ]),
                'total'        => $sizes->total(),
                'per_page'     => $sizes->perPage(),
                'current_page' => $sizes->currentPage(),
                'last_page'    => $sizes->lastPage(),
                'from'         => $sizes->firstItem() ?? 0,
                'to'           => $sizes->lastItem() ?? 0,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:50',
            'display_name' => 'nullable|string|max:100',
            'category'     => 'required|in:clothing,bottoms,footwear,other',
            'sort_order'   => 'nullable|integer|min:0',
            'is_active'    => 'boolean',
        ]);
        // Unique check
        $exists = Size::where('name', $validated['name'])->where('category', $validated['category'])->exists();
        if ($exists) return response()->json(['error' => 'Size already exists in this category.'], 422);

        $size = Size::create($validated);
        return response()->json(['success' => true, 'size' => $size]);
    }

    public function update(Request $request, Size $size)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:50',
            'display_name' => 'nullable|string|max:100',
            'category'     => 'required|in:clothing,bottoms,footwear,other',
            'sort_order'   => 'nullable|integer|min:0',
            'is_active'    => 'boolean',
        ]);
        $size->update($validated);
        return response()->json(['success' => true, 'size' => $size]);
    }

    public function toggle(Size $size)
    {
        $size->update(['is_active' => !$size->is_active]);
        return response()->json(['success' => true, 'is_active' => $size->is_active]);
    }

    public function destroy(Size $size)
    {
        if ($size->variants()->count() > 0) {
            return response()->json(['error' => 'Cannot delete — this size is used in '.$size->variants()->count().' variants.'], 422);
        }
        $size->delete();
        return response()->json(['success' => true]);
    }
}