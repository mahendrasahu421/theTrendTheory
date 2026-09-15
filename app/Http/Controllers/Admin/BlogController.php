<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function __construct(private CloudinaryService $cloudinary)
    {
    }

    public function index(Request $request)
    {
        $query = Blog::where('type', 'blog');

        // Filter by Status (published / draft)
        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        // Filter by Category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Search Query
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('author_name', 'like', "%{$search}%")
                    ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        // Dynamic Sorting
        $sort = $request->get('sort', 'latest');
        if ($sort === 'views') {
            $query->orderByDesc('views_count');
        } elseif ($sort === 'oldest') {
            $query->oldest('created_at');
        } elseif ($sort === 'featured') {
            $query->orderByDesc('is_featured')->latest('created_at');
        } else {
            $query->latest('published_at')->latest('created_at');
        }

        $blogs = $query->paginate(15)->withQueryString();

        // Dynamic Monthly Comparisons
        $thisMonthBlogs = Blog::where('type', 'blog')->where('created_at', '>=', now()->startOfMonth())->count();
        $lastMonthBlogs = Blog::where('type', 'blog')->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->count();
        $blogGrowth = $lastMonthBlogs > 0 ? round((($thisMonthBlogs - $lastMonthBlogs) / $lastMonthBlogs) * 100) : ($thisMonthBlogs > 0 ? 100 : 0);

        $thisMonthPub = Blog::where('type', 'blog')->where('is_published', true)->where('published_at', '>=', now()->startOfMonth())->count();
        $lastMonthPub = Blog::where('type', 'blog')->where('is_published', true)->whereBetween('published_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->count();
        $pubGrowth = $lastMonthPub > 0 ? round((($thisMonthPub - $lastMonthPub) / $lastMonthPub) * 100) : ($thisMonthPub > 0 ? 100 : 0);

        // Statistics for Blogs
        $stats = [
            'total' => Blog::where('type', 'blog')->count(),
            'published' => Blog::where('type', 'blog')->where('is_published', true)->count(),
            'drafts' => Blog::where('type', 'blog')->where('is_published', false)->count(),
            'featured' => Blog::where('type', 'blog')->where('is_featured', true)->count(),
            'total_views' => (int) Blog::where('type', 'blog')->sum('views_count'),
            'total_news' => Blog::where('type', 'news')->count(),
            'blog_growth' => $blogGrowth,
            'pub_growth' => $pubGrowth,
        ];

        $categories = Blog::where('type', 'blog')->select('category')->distinct()->pluck('category')->filter()->values();

        return view('admin.blogs.index', compact('blogs', 'stats', 'categories'));
    }

    public function create()
    {
        $categories = [
            'Fashion Trends',
            'Style Guide',
            'Lookbook & Outfits',
            'Fabric & Craftsmanship',
            'Sustainability',
            'Streetwear Culture',
            'Seasonal Wardrobe',
        ];

        return view('admin.blogs.form', [
            'blog' => new Blog(['type' => 'blog']),
            'isEdit' => false,
            'categories' => $categories,
            'title' => 'Write New Blog Article',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'category' => 'required|string|max:100',
            'summary' => 'nullable|string|max:1000',
            'content' => 'required|string',
            'author_name' => 'nullable|string|max:100',
            'read_time' => 'nullable|string|max:50',
            'tags' => 'nullable|string|max:255',
            'is_published' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'published_at' => 'nullable|date',
        ]);

        $slug = !empty($validated['slug'])
            ? Blog::generateUniqueSlug($validated['slug'])
            : Blog::generateUniqueSlug($validated['title']);

        $readTime = $validated['read_time'];
        if (empty($readTime)) {
            $wordCount = str_word_count(strip_tags($validated['content']));
            $minutes = max(1, (int) ceil($wordCount / 200));
            $readTime = "{$minutes} min read";
        }

        $imageUrl = null;
        $imagePublicId = null;

        if ($request->hasFile('image')) {
            $uploaded = $this->cloudinary->upload($request->file('image'), 'blogs');
            $imageUrl = $uploaded['url'] ?? null;
            $imagePublicId = $uploaded['public_id'] ?? null;
        }

        $isPublished = $request->boolean('is_published', true);
        $publishedAt = $validated['published_at'] ?? ($isPublished ? now() : null);

        $blog = Blog::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'type' => 'blog',
            'category' => $validated['category'],
            'summary' => $validated['summary'] ?? Str::limit(strip_tags($validated['content']), 180),
            'content' => $validated['content'],
            'image_url' => $imageUrl,
            'image_public_id' => $imagePublicId,
            'author_name' => $validated['author_name'] ?: 'Vayu Editorial',
            'read_time' => $readTime,
            'tags' => $validated['tags'] ?? null,
            'is_published' => $isPublished,
            'is_featured' => $request->boolean('is_featured'),
            'published_at' => $publishedAt,
        ]);

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog article "' . $blog->title . '" created successfully.');
    }

    public function edit(Blog $blog)
    {
        $categories = [
            'Fashion Trends',
            'Style Guide',
            'Lookbook & Outfits',
            'Fabric & Craftsmanship',
            'Sustainability',
            'Streetwear Culture',
            'Seasonal Wardrobe',
        ];

        return view('admin.blogs.form', [
            'blog' => $blog,
            'isEdit' => true,
            'categories' => $categories,
            'title' => 'Edit Blog: ' . $blog->title,
        ]);
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'category' => 'required|string|max:100',
            'summary' => 'nullable|string|max:1000',
            'content' => 'required|string',
            'author_name' => 'nullable|string|max:100',
            'read_time' => 'nullable|string|max:50',
            'tags' => 'nullable|string|max:255',
            'is_published' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'published_at' => 'nullable|date',
        ]);

        $slug = !empty($validated['slug']) && $validated['slug'] !== $blog->slug
            ? Blog::generateUniqueSlug($validated['slug'], $blog->id)
            : ($validated['title'] !== $blog->title
                ? Blog::generateUniqueSlug($validated['title'], $blog->id)
                : $blog->slug);

        $imageUrl = $blog->image_url;
        $imagePublicId = $blog->image_public_id;

        if ($request->hasFile('image')) {
            $uploaded = $this->cloudinary->upload($request->file('image'), 'blogs');
            if ($blog->image_public_id) {
                $this->cloudinary->delete($blog->image_public_id);
            }
            $imageUrl = $uploaded['url'] ?? null;
            $imagePublicId = $uploaded['public_id'] ?? null;
        }

        $readTime = $validated['read_time'];
        if (empty($readTime)) {
            $wordCount = str_word_count(strip_tags($validated['content']));
            $minutes = max(1, (int) ceil($wordCount / 200));
            $readTime = "{$minutes} min read";
        }

        $isPublished = $request->boolean('is_published');
        $publishedAt = $validated['published_at'] ?? ($isPublished && !$blog->published_at ? now() : $blog->published_at);

        $blog->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'category' => $validated['category'],
            'summary' => $validated['summary'] ?? Str::limit(strip_tags($validated['content']), 180),
            'content' => $validated['content'],
            'image_url' => $imageUrl,
            'image_public_id' => $imagePublicId,
            'author_name' => $validated['author_name'] ?: 'Vayu Editorial',
            'read_time' => $readTime,
            'tags' => $validated['tags'] ?? null,
            'is_published' => $isPublished,
            'is_featured' => $request->boolean('is_featured'),
            'published_at' => $publishedAt,
        ]);

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog article "' . $blog->title . '" updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        if ($blog->image_public_id) {
            $this->cloudinary->delete($blog->image_public_id);
        }

        $blog->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Blog deleted successfully.']);
        }

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog article deleted successfully.');
    }

    public function toggleStatus(Blog $blog)
    {
        $blog->update([
            'is_published' => !$blog->is_published,
            'published_at' => !$blog->is_published && !$blog->published_at ? now() : $blog->published_at,
        ]);

        return response()->json([
            'success' => true,
            'is_published' => $blog->is_published,
            'message' => 'Status updated to ' . ($blog->is_published ? 'Published' : 'Draft'),
        ]);
    }

    public function toggleFeatured(Blog $blog)
    {
        $blog->update([
            'is_featured' => !$blog->is_featured,
        ]);

        return response()->json([
            'success' => true,
            'is_featured' => $blog->is_featured,
            'message' => 'Featured status updated',
        ]);
    }
}
