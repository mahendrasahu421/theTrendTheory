<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'all'); // 'all', 'blog', 'news'
        $category = $request->get('category');
        $search = $request->get('search');

        $query = Blog::published()->latest('published_at');

        if ($type !== 'all' && in_array($type, ['blog', 'news'])) {
            $query->where('type', $type);
        }

        if (!empty($category)) {
            $query->where('category', $category);
        }

        if (!empty($search)) {
            $search = trim($search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        // Featured post for the top banner
        $featuredPost = Blog::published()->featured()->latest('published_at')->first();

        // Main articles list
        $posts = $query->when($featuredPost && empty($search) && empty($category) && $type === 'all', function ($q) use ($featuredPost) {
            $q->where('id', '!=', $featuredPost->id);
        })->paginate(9)->withQueryString();

        // All distinct categories
        $categories = Blog::published()->select('category')->distinct()->pluck('category')->filter()->values();

        // Recent posts sidebar
        $recentPosts = Blog::published()->latest('published_at')->take(5)->get();

        $pageTitle = $type === 'news' ? 'Latest Fashion News & Press' : ($type === 'blog' ? 'Style Stories & Trends' : 'The Trend Journal — Blogs & News');

        return view('froentend.blogs.index', compact('posts', 'featuredPost', 'categories', 'recentPosts', 'type', 'category', 'search', 'pageTitle'));
    }

    public function show(string $slug)
    {
        $post = Blog::where('slug', $slug)->firstOrFail();

        // If not published, allow only logged-in staff/admin to preview
        if (!$post->is_published) {
            $user = auth()->user();
            if (!$user || !($user->isAdmin() || $user->isSuperAdmin())) {
                abort(404);
            }
        }

        // Record view safely
        $post->recordView();

        // Related posts
        $relatedPosts = Blog::published()
            ->where('id', '!=', $post->id)
            ->where(function ($q) use ($post) {
                $q->where('category', $post->category)
                    ->orWhere('type', $post->type);
            })
            ->latest('published_at')
            ->take(3)
            ->get();

        // SEO Schema
        $articleSchema = json_encode([
            '@context' => 'https://schema.org',
            '@type' => $post->type === 'news' ? 'NewsArticle' : 'BlogPosting',
            'headline' => $post->title,
            'image' => [$post->image_url ?? asset('images/og-default.jpg')],
            'datePublished' => $post->published_at ? $post->published_at->toIso8601String() : $post->created_at->toIso8601String(),
            'dateModified' => $post->updated_at->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $post->author_name,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Vayu',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('images/logo.png'),
                ],
            ],
            'description' => $post->summary,
        ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        return view('froentend.blogs.show', compact('post', 'relatedPosts', 'articleSchema'));
    }
}
