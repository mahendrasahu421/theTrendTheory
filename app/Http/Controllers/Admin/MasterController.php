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
                [
                    'is_active' => (bool) $item->is_active,
                    'hex' => $item->hex ?? $item->hex_code ?? null,
                    'image' => $item->image ?? null,
                ]
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
        $nameRule = \Illuminate\Validation\Rule::unique($type === 'sizes' ? 'sizes' : 'colors', 'name');

        if ($cfg['has_type']) {
            $nameRule->where('type', $request->input('type', 'clothing'));
        }

        $rules = [
            'name' => [
                'required',
                'string',
                'max:100',
                $nameRule,
            ],
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ];

        if ($cfg['has_hex']) {
            $rules['hex'] = ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'];
        }

        if ($type === 'colors') {
            $rules['image'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120';
        }

        if ($cfg['has_type']) {
            $rules['type'] = ['nullable', 'string', \Illuminate\Validation\Rule::in(array_keys($cfg['type_options']))];
        }

        if ($cfg['has_measurements']) {
            $rules['label'] = 'nullable|string|max:100';
            $rules['chest'] = 'nullable|string|max:100';
            $rules['waist'] = 'nullable|string|max:100';
            $rules['length'] = 'nullable|string|max:100';
        }

        $request->validate($rules);

        $data = [
            'name' => $request->name,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($cfg['has_hex']) {
            $data['hex'] = $request->hex ?? '#000000';
        }

        if ($type === 'colors') {
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $upload = app(\App\Services\CloudinaryService::class)->upload($request->file('image'), 'colors');
                $data['image'] = $upload['url'] ?? null;
            }
            if ($cfg['has_hex'] && !empty($data['hex'])) {
                $data['hex_code'] = $data['hex'];
            }
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
        $nameRule = \Illuminate\Validation\Rule::unique($type === 'sizes' ? 'sizes' : 'colors', 'name')->ignore($id);

        if ($cfg['has_type']) {
            $nameRule->where('type', $request->input('type', 'clothing'));
        }

        $rules = [
            'name' => [
                'required',
                'string',
                'max:100',
                $nameRule,
            ],
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ];

        if ($cfg['has_hex']) {
            $rules['hex'] = ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'];
        }

        if ($type === 'colors') {
            $rules['image'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120';
            $rules['remove_image'] = 'nullable|boolean';
        }

        if ($cfg['has_type']) {
            $rules['type'] = ['nullable', 'string', \Illuminate\Validation\Rule::in(array_keys($cfg['type_options']))];
        }

        if ($cfg['has_measurements']) {
            $rules['label'] = 'nullable|string|max:100';
            $rules['chest'] = 'nullable|string|max:100';
            $rules['waist'] = 'nullable|string|max:100';
            $rules['length'] = 'nullable|string|max:100';
        }

        $request->validate($rules);

        $data = [
            'name' => $request->name,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($cfg['has_hex']) {
            $data['hex'] = $request->hex ?? '#000000';
        }

        if ($type === 'colors') {
            if ($request->boolean('remove_image')) {
                $data['image'] = null;
            } elseif ($request->hasFile('image') && $request->file('image')->isValid()) {
                $upload = app(\App\Services\CloudinaryService::class)->upload($request->file('image'), 'colors');
                $data['image'] = $upload['url'] ?? null;
            }
            if ($cfg['has_hex'] && !empty($data['hex'])) {
                $data['hex_code'] = $data['hex'];
            }
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
        $item = $model::findOrFail($id);

        if (method_exists($item, 'variants') && $item->variants()->exists()) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete this {$type} item because it is used in product variants.",
            ], 422);
        }

        $item->delete();
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
