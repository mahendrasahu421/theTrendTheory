<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    private function getModel(string $type)
    {
        return match ($type) {
            'sizes' => \App\Models\Size::class,
            'colors' => \App\Models\Color::class,
            default => abort(404),
        };
    }

    private function getConfig(string $type): array
    {
        return match ($type) {
            'sizes' => [
                'title' => 'Sizes',
                'single' => 'Size',
                'has_hex' => false,
                'has_measurements' => true,
                'has_type' => true,
                'type_options' => [
                    'clothing' => 'Clothing (T-Shirt, Top)',
                    'bottomwear' => 'Bottomwear (Pants, Shorts)',
                    'footwear' => 'Footwear',
                ],
            ],
            'colors' => [
                'title' => 'Colors',
                'single' => 'Color',
                'has_hex' => true,
                'has_measurements' => false,
                'has_type' => false,
                'type_options' => [],
            ],
            default => abort(404),
        };
    }

    public function index(string $type)
    {
        $cfg = $this->getConfig($type);
        $model = $this->getModel($type);
        $items = $model::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.masters.index', compact('type', 'cfg', 'items'));
    }

    public function ajax(Request $request, string $type)
    {
        $cfg = $this->getConfig($type);
        $model = $this->getModel($type);

        $perPage = (int) $request->get('per_page', 10);
        $page = (int) $request->get('page', 1);
        $search = trim($request->get('search', ''));
        $status = $request->get('status', '');
        $typeFilter = $request->get('type', '');

        $q = $model::query();

        // Search by name
        if ($search) {
            $q->where('name', 'like', '%' . $search . '%');
        }

        // Filter by active status
        if ($status !== '') {
            $q->where('is_active', (bool) (int) $status);
        }

        // Filter by type (only if model has 'type' column)
        if ($typeFilter && $cfg['has_type']) {
            $q->where('type', $typeFilter);
        }

        $q->orderBy('sort_order')->orderBy('name');

        $result = $q->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $result->map(fn($item) => array_merge(
                $item->toArray(),
                ['is_active' => (bool) $item->is_active]
            )),
            'total' => $result->total(),
            'per_page' => $result->perPage(),
            'current_page' => $result->currentPage(),
            'last_page' => $result->lastPage(),
            'from' => $result->firstItem() ?? 0,
            'to' => $result->lastItem() ?? 0,
        ]);
    }

    public function store(Request $request, string $type)
    {
        $cfg = $this->getConfig($type);
        $model = $this->getModel($type);

        $request->validate([
            'name' => 'required|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = [
            'name' => $request->name,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($cfg['has_hex']) {
            $data['hex'] = $request->hex ?? '#000000';
        }

        if ($cfg['has_measurements']) {
            $data['label'] = $request->label;
            $data['chest'] = $request->chest;
            $data['waist'] = $request->waist;
            $data['length'] = $request->length;
        }

        if ($cfg['has_type']) {
            $data['type'] = $request->type ?? 'clothing';
        }

        $model::create($data);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, string $type, int $id)
    {
        $cfg = $this->getConfig($type);
        $model = $this->getModel($type);
        $item = $model::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $data = [
            'name' => $request->name,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($cfg['has_hex']) {
            $data['hex'] = $request->hex ?? '#000000';
        }

        if ($cfg['has_measurements']) {
            $data['label'] = $request->label;
            $data['chest'] = $request->chest;
            $data['waist'] = $request->waist;
            $data['length'] = $request->length;
        }

        if ($cfg['has_type']) {
            $data['type'] = $request->type ?? 'clothing';
        }

        $item->update($data);

        return response()->json(['success' => true]);
    }

    public function destroy(string $type, int $id)
    {
        $model = $this->getModel($type);
        $model::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    public function toggle(string $type, int $id)
    {
        $model = $this->getModel($type);
        $item = $model::findOrFail($id);
        $item->update(['is_active' => !$item->is_active]);
        return response()->json(['success' => true, 'is_active' => (bool) $item->is_active]);
    }
}