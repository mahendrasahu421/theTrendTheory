{{-- resources/views/admin/inventory/partials/table_rows.blade.php --}}
@forelse ($products as $p)
    @php
        $isOut = $p->stock <= 0;
        $isLow = $p->stock > 0 && $p->stock <= ($p->low_stock_alert ?: 10);
        $stockPercent = min(100, max(0, round(($p->stock / 50) * 100)));
        $imgSrc = $p->image ? (Str::startsWith($p->image, ['http://', 'https://']) ? $p->image : asset($p->image)) : null;
    @endphp
    <tr class="modern-table-row" id="productRow{{ $p->id }}">
        {{-- Product info --}}
        <td>
            <div class="d-flex align-items-center gap-3">
                @if ($imgSrc)
                    <img src="{{ $imgSrc }}" 
                         class="product-img-box"
                         alt="{{ $p->name }}"
                         onerror="this.onerror=null;this.src='https://placehold.co/80x80/f1f5f9/94a3b8?text=Product';">
                @else
                    <div class="product-img-box d-flex align-items-center justify-content-center text-muted font-xs">
                        <i class="bi bi-image"></i>
                    </div>
                @endif
                <div>
                    <a href="{{ route('admin.products.edit', $p) }}" style="font-size: 13px; font-weight: 700; color: #0f172a; text-decoration: none;" class="d-block">
                        {{ Str::limit($p->name, 36) }}
                    </a>
                    <span class="text-muted font-xs">SKU: <strong class="text-navy">{{ $p->sku ?: 'TTT-'.$p->id }}</strong> &bull; {{ $p->color_name ?: 'Default' }}</span>
                </div>
            </div>
        </td>

        {{-- Category --}}
        <td>
            <span class="badge bg-light text-navy border font-xs py-1 px-2">
                {{ optional($p->category)->name ?: 'Uncategorized' }}
            </span>
        </td>

        {{-- Sold count --}}
        <td>
            <strong class="font-xs text-navy">{{ number_format($p->total_sold) }}</strong>
            <span class="text-muted font-xs">units</span>
        </td>

        {{-- Price --}}
        <td>
            <strong class="text-navy">₹{{ number_format($p->price) }}</strong>
        </td>

        {{-- Stock with Progress Bar --}}
        <td>
            <div class="stock-progress-wrap">
                <div class="d-flex justify-content-between font-xs font-weight-bold mb-1">
                    <span id="stockDisplay{{ $p->id }}" style="font-weight: 800; color: {{ $isOut ? '#dc2626' : ($isLow ? '#d97706' : '#059669') }};">
                        {{ $p->stock }} units
                    </span>
                </div>
                <div class="stock-bar-track">
                    <div class="stock-bar-fill" 
                         id="stockBar{{ $p->id }}"
                         style="width: {{ $stockPercent }}%; background: {{ $isOut ? '#dc2626' : ($isLow ? '#f59e0b' : '#10b981') }};">
                    </div>
                </div>
            </div>
        </td>

        {{-- Status Badge --}}
        <td>
            <span class="status-badge-stock {{ $isOut ? 'stock-badge-red' : ($isLow ? 'stock-badge-amber' : 'stock-badge-green') }}" id="statusBadge{{ $p->id }}">
                @if ($isOut)
                    <i class="bi bi-x-circle-fill me-1"></i> Out of Stock
                @elseif ($isLow)
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Low Stock
                @else
                    <i class="bi bi-check-circle-fill me-1"></i> In Stock
                @endif
            </span>
        </td>

        {{-- Quick Adjust Actions --}}
        <td style="text-align: right;">
            <div class="quick-adjust-group justify-content-end">
                <button type="button" class="btn-quick-qty" onclick="adjustStock({{ $p->id }}, 'add', 5)" title="Add +5 units">+5</button>
                <button type="button" class="btn-quick-qty" onclick="adjustStock({{ $p->id }}, 'add', 10)" title="Add +10 units">+10</button>
                <button type="button" class="btn-quick-qty" onclick="adjustStock({{ $p->id }}, 'add', 50)" title="Add +50 units">+50</button>
                
                <input type="number" 
                       id="directStockInput{{ $p->id }}" 
                       value="{{ $p->stock }}" 
                       class="input-stock-direct" 
                       min="0">
                
                <button type="button" class="btn-save-stock" onclick="saveDirectStock({{ $p->id }})" title="Save Stock">
                    <i class="bi bi-check-lg"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" style="text-align: center; padding: 50px 20px; color: #64748b;">
            <i class="bi bi-boxes" style="font-size: 36px; color: #cbd5e1; display: block; margin-bottom: 8px;"></i>
            <strong style="color: #0f172a; font-size: 14px; display: block;">No inventory items match filter</strong>
            <p style="font-size: 12px; margin: 4px 0 16px;">Try adjusting your search criteria.</p>
        </td>
    </tr>
@endforelse
