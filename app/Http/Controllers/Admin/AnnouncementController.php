<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function __construct(private CloudinaryService $cloudinary)
    {
    }

    public function index()
    {
        $announcements = Announcement::orderBy('sort_order')->orderByDesc('created_at')->get();
        return view('admin.announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'text' => 'required|string|max:200',
            'link' => 'nullable|string|max:500',
            'link_text' => 'nullable|string|max:100',
            'bg_color' => 'nullable|string|max:20',
            'text_color' => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $uploaded = $this->cloudinary->upload($request->file('image'), 'announcements');
            $validated['image_url'] = $uploaded['url'];
            $validated['image_public_id'] = $uploaded['public_id'];
        }

        Announcement::create([
            'text'       => $validated['text'],
            'link'       => $validated['link'] ?? null,
            'link_text'  => $validated['link_text'] ?? null,
            'bg_color'   => $validated['bg_color'] ?? '#00285a',
            'text_color' => $validated['text_color'] ?? '#ffffff',
            'image_url'  => $validated['image_url'] ?? null,
            'image_public_id' => $validated['image_public_id'] ?? null,
            'is_active'  => $request->boolean('is_active', true),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'text' => 'required|string|max:200',
            'link' => 'nullable|string|max:500',
            'link_text' => 'nullable|string|max:100',
            'bg_color' => 'nullable|string|max:20',
            'text_color' => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $uploaded = $this->cloudinary->upload($request->file('image'), 'announcements');
            $this->cloudinary->delete($announcement->image_public_id);
            $validated['image_url'] = $uploaded['url'];
            $validated['image_public_id'] = $uploaded['public_id'];
        }

        $announcement->update([
            'text'       => $validated['text'],
            'link'       => $validated['link'] ?? null,
            'link_text'  => $validated['link_text'] ?? null,
            'bg_color'   => $validated['bg_color'] ?? '#00285a',
            'text_color' => $validated['text_color'] ?? '#ffffff',
            'image_url'  => $validated['image_url'] ?? $announcement->image_url,
            'image_public_id' => $validated['image_public_id'] ?? $announcement->image_public_id,
            'is_active'  => $request->boolean('is_active'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy(Announcement $announcement)
    {
        $this->cloudinary->delete($announcement->image_public_id);
        $announcement->delete();
        return response()->json(['success' => true]);
    }

    public function toggle(Announcement $announcement)
    {
        $announcement->update(['is_active' => !$announcement->is_active]);
        return response()->json(['success' => true]);
    }
}
