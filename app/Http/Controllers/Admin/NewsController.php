<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function __construct(private CloudinaryService $cloudinary)
    {
    }

    public function index(Request $request)
    {
        $query = Blog::where('type', 'news');

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

        $news = $query->paginate(15)->withQueryString();

        // Dynamic Monthly Comparisons
        $thisMonthNews = Blog::where('type', 'news')->where('created_at', '>=', now()->startOfMonth())->count();
        $lastMonthNews = Blog::where('type', 'news')->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->count();
        $newsGrowth = $lastMonthNews > 0 ? round((($thisMonthNews - $lastMonthNews) / $lastMonthNews) * 100) : ($thisMonthNews > 0 ? 100 : 0);

        $thisMonthPub = Blog::where('type', 'news')->where('is_published', true)->where('published_at', '>=', now()->startOfMonth())->count();
        $lastMonthPub = Blog::where('type', 'news')->where('is_published', true)->whereBetween('published_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->count();
        $pubGrowth = $lastMonthPub > 0 ? round((($thisMonthPub - $lastMonthPub) / $lastMonthPub) * 100) : ($thisMonthPub > 0 ? 100 : 0);

        // Statistics for News
        $stats = [
            'total' => Blog::where('type', 'news')->count(),
            'published' => Blog::where('type', 'news')->where('is_published', true)->count(),
            'drafts' => Blog::where('type', 'news')->where('is_published', false)->count(),
            'featured' => Blog::where('type', 'news')->where('is_featured', true)->count(),
            'total_views' => (int) Blog::where('type', 'news')->sum('views_count'),
            'total_blogs' => Blog::where('type', 'blog')->count(),
            'news_growth' => $newsGrowth,
            'pub_growth' => $pubGrowth,
        ];

        $categories = Blog::where('type', 'news')->select('category')->distinct()->pluck('category')->filter()->values();

        return view('admin.news.index', compact('news', 'stats', 'categories'));
    }

    public function create()
    {
        $categories = [
            'Company News',
            'Press Release',
            'Product Drops & Launches',
            'Events & Popups',
            'Logistics & Expansion',
            'Brand Collaborations',
            'Official Announcements',
        ];

        return view('admin.news.form', [
            'news' => new Blog(['type' => 'news']),
            'isEdit' => false,
            'categories' => $categories,
            'title' => 'Publish New Press Release / News',
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
            $uploaded = $this->cloudinary->upload($request->file('image'), 'news');
            $imageUrl = $uploaded['url'] ?? null;
            $imagePublicId = $uploaded['public_id'] ?? null;
        }

        $isPublished = $request->boolean('is_published', true);
        $publishedAt = $validated['published_at'] ?? ($isPublished ? now() : null);

        $news = Blog::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'type' => 'news',
            'category' => $validated['category'],
            'summary' => $validated['summary'] ?? Str::limit(strip_tags($validated['content']), 180),
            'content' => $validated['content'],
            'image_url' => $imageUrl,
            'image_public_id' => $imagePublicId,
            'author_name' => $validated['author_name'] ?: 'Vayu PR Team',
            'read_time' => $readTime,
            'tags' => $validated['tags'] ?? null,
            'is_published' => $isPublished,
            'is_featured' => $request->boolean('is_featured'),
            'published_at' => $publishedAt,
        ]);

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'News announcement "' . $news->title . '" published successfully.');
    }

    public function edit(Blog $news)
    {
        $categories = [
            'Company News',
            'Press Release',
            'Product Drops & Launches',
            'Events & Popups',
            'Logistics & Expansion',
            'Brand Collaborations',
            'Official Announcements',
        ];

        return view('admin.news.form', [
            'news' => $news,
            'isEdit' => true,
            'categories' => $categories,
            'title' => 'Edit News: ' . $news->title,
        ]);
    }

    public function update(Request $request, Blog $news)
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

        $slug = !empty($validated['slug']) && $validated['slug'] !== $news->slug
            ? Blog::generateUniqueSlug($validated['slug'], $news->id)
            : ($validated['title'] !== $news->title
                ? Blog::generateUniqueSlug($validated['title'], $news->id)
                : $news->slug);

        $imageUrl = $news->image_url;
        $imagePublicId = $news->image_public_id;

        if ($request->hasFile('image')) {
            $uploaded = $this->cloudinary->upload($request->file('image'), 'news');
            if ($news->image_public_id) {
                $this->cloudinary->delete($news->image_public_id);
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
        $publishedAt = $validated['published_at'] ?? ($isPublished && !$news->published_at ? now() : $news->published_at);

        $news->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'category' => $validated['category'],
            'summary' => $validated['summary'] ?? Str::limit(strip_tags($validated['content']), 180),
            'content' => $validated['content'],
            'image_url' => $imageUrl,
            'image_public_id' => $imagePublicId,
            'author_name' => $validated['author_name'] ?: 'Vayu PR Team',
            'read_time' => $readTime,
            'tags' => $validated['tags'] ?? null,
            'is_published' => $isPublished,
            'is_featured' => $request->boolean('is_featured'),
            'published_at' => $publishedAt,
        ]);

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'News announcement "' . $news->title . '" updated successfully.');
    }

    public function destroy(Blog $news)
    {
        if ($news->image_public_id) {
            $this->cloudinary->delete($news->image_public_id);
        }

        $news->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'News deleted successfully.']);
        }

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'News announcement deleted successfully.');
    }

    public function toggleStatus(Blog $news)
    {
        $news->update([
            'is_published' => !$news->is_published,
            'published_at' => !$news->is_published && !$news->published_at ? now() : $news->published_at,
        ]);

        return response()->json([
            'success' => true,
            'is_published' => $news->is_published,
            'message' => 'Status updated to ' . ($news->is_published ? 'Published' : 'Draft'),
        ]);
    }

    public function toggleFeatured(Blog $news)
    {
        $news->update([
            'is_featured' => !$news->is_featured,
        ]);

        return response()->json([
            'success' => true,
            'is_featured' => $news->is_featured,
            'message' => 'Featured status updated',
        ]);
    }
}
