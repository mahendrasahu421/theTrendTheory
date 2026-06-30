<?php
// app/Http/Controllers/Admin/CategoryController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct(private readonly CloudinaryService $cloudinary)
    {
        // no-op
    }

    // ── Index page ─────────────────────────────────────
    public function index()
    {
        return view('admin.categories.index');
    }

    // ── AJAX — DataTable data ──────────────────────────
    public function ajax(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $page = (int) $request->get('page', 1);
        $search = trim($request->get('search', ''));
        $type = $request->get('parent', '');
        $status = $request->get('status', '');

        $q = Category::with('parent')->withCount('products');

        if ($search) {
            $q->where(function ($qq) use ($search) {
                $qq->where('name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%');
            });
        }

        if ($type === 'parent')
            $q->whereNull('parent_id');
        if ($type === 'child')
            $q->whereNotNull('parent_id');

        if ($status !== '')
            $q->where('is_active', (bool) (int) $status);

        $q->orderByRaw('COALESCE(parent_id, id)')->orderBy('sort_order')->orderBy('name');

        $result = $q->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $result->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'slug' => $c->slug,
                'parent_id' => $c->parent_id,
                'parent_name' => $c->parent->name ?? null,
                'products_count' => $c->products_count,
                'show_in_nav' => (bool) $c->show_in_nav,
                'show_in_home' => (bool) $c->show_in_home,
                'is_active' => (bool) $c->is_active,
                'image' => $c->image,
                'edit_url' => route('admin.categories.edit', $c),
            ]),
            'total' => $result->total(),
            'per_page' => $result->perPage(),
            'current_page' => $result->currentPage(),
            'last_page' => $result->lastPage(),
            'from' => $result->firstItem() ?? 0,
            'to' => $result->lastItem() ?? 0,
        ]);
    }

    // ── Toggle active status ────────────────────────────
    public function toggle(Category $category)
    {
        $category->update(['is_active' => !$category->is_active]);
        return response()->json(['success' => true, 'is_active' => (bool) $category->is_active]);
    }

    // ── Create ─────────────────────────────────────────
    public function create()
    {
        $parents = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')->orderBy('name')->get();
        return view('admin.categories.create', compact('parents'));
    }

    // ── Store ──────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',

            // Accept either existing URL/string OR a newly uploaded file.
            'image' => ['nullable'],
            'banner_image' => ['nullable'],

            'meta_title' => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:170',
            'meta_keywords' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',

            'is_active' => 'sometimes|boolean',
            'show_in_nav' => 'sometimes|boolean',
            'show_in_home' => 'sometimes|boolean',
        ]);

        $imageUrl = $request->input('image');
        $bannerImageUrl = $request->input('banner_image');

        if ($request->hasFile('image')) {
            $uploaded = $this->cloudinary->upload($request->file('image'), 'categories');
            $imageUrl = $uploaded['url'] ?? null;
        }

        if ($request->hasFile('banner_image')) {
            $uploaded = $this->cloudinary->upload($request->file('banner_image'), 'categories/banners');
            $bannerImageUrl = $uploaded['url'] ?? null;
        }

        // Unique slug generate karo
        $slug = Str::slug($request->name);
        $orig = $slug;
        $n = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $orig . '-' . $n++;
        }

        Category::create([
            'name' => $request->name,
            'slug' => $slug,
            'parent_id' => $request->parent_id ?: null,
            'description' => $request->description,
            'image' => $imageUrl,
            'banner_image' => $bannerImageUrl,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->boolean('is_active', true),
            'show_in_nav' => $request->boolean('show_in_nav', true),
            'show_in_home' => $request->boolean('show_in_home', false),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category "' . $request->name . '" created successfully!');
    }

    // ── Edit ───────────────────────────────────────────
    public function edit(Category $category)
    {
        $parents = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->where('id', '!=', $category->id)
            ->orderBy('sort_order')->orderBy('name')->get();
        return view('admin.categories.create', compact('category', 'parents'));
    }

    // ── Update ─────────────────────────────────────────
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',

            // Accept either existing URL/string OR a newly uploaded file.
            'image' => ['nullable'],
            'banner_image' => ['nullable'],

            'meta_title' => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:170',
            'meta_keywords' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',

            'is_active' => 'sometimes|boolean',
            'show_in_nav' => 'sometimes|boolean',
            'show_in_home' => 'sometimes|boolean',
        ]);

        $imageUrl = $request->input('image');
        $bannerImageUrl = $request->input('banner_image');

        if ($request->hasFile('image')) {
            $uploaded = $this->cloudinary->upload($request->file('image'), 'categories');
            $imageUrl = $uploaded['url'] ?? null;
        }

        if ($request->hasFile('banner_image')) {
            $uploaded = $this->cloudinary->upload($request->file('banner_image'), 'categories/banners');
            $bannerImageUrl = $uploaded['url'] ?? null;
        }

        $category->update([
            'name' => $request->name,
            'parent_id' => $request->parent_id ?: null,
            'description' => $request->description,
            'image' => $imageUrl,
            'banner_image' => $bannerImageUrl,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->boolean('is_active'),
            'show_in_nav' => $request->boolean('show_in_nav'),
            'show_in_home' => $request->boolean('show_in_home'),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category "' . $category->name . '" updated successfully!');
    }

    // ── Destroy ────────────────────────────────────────
    public function destroy(Category $category)
    {
        $category->update(['is_active' => false]);
        return back()->with('success', 'Category deactivated.');
    }
}