<?php
// app/Http/Controllers/Admin/ColorController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index() { return view('admin.variants.colors'); }

    public function ajax(Request $request)
    {
        try {
            $perPage = (int) $request->get('per_page', 25);
            $page    = (int) $request->get('page', 1);
            $search  = $request->get('search', '');
            $status  = $request->get('status', '');

            $query = Color::withCount('variants');

            if (!empty($search)) $query->where('name', 'LIKE', "%{$search}%");
            if ($status !== '')  $query->where('is_active', $status == '1');

            $query->orderBy('sort_order')->orderBy('name');
            $colors = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'data'         => $colors->map(fn($c) => [
                    'id'            => $c->id,
                    'name'          => $c->name,
                    'hex_code'      => $c->hex_code,
                    'sort_order'    => $c->sort_order,
                    'is_active'     => (bool) $c->is_active,
                    'variants_count'=> $c->variants_count,
                ]),
                'total'        => $colors->total(),
                'per_page'     => $colors->perPage(),
                'current_page' => $colors->currentPage(),
                'last_page'    => $colors->lastPage(),
                'from'         => $colors->firstItem() ?? 0,
                'to'           => $colors->lastItem() ?? 0,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:100|unique:colors,name',
            'hex_code'   => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'boolean',
        ]);
        $color = Color::create($validated);
        return response()->json(['success' => true, 'color' => $color]);
    }

    public function update(Request $request, Color $color)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:100|unique:colors,name,'.$color->id,
            'hex_code'   => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'boolean',
        ]);
        $color->update($validated);
        return response()->json(['success' => true, 'color' => $color]);
    }

    public function toggle(Color $color)
    {
        $color->update(['is_active' => !$color->is_active]);
        return response()->json(['success' => true, 'is_active' => $color->is_active]);
    }

    public function destroy(Color $color)
    {
        if ($color->variants()->count() > 0) {
            return response()->json(['error' => 'Cannot delete — this color is used in '.$color->variants()->count().' variants.'], 422);
        }
        $color->delete();
        return response()->json(['success' => true]);
    }
}