{{-- resources/views/admin/coupons/form.blade.php --}}
@extends('admin.layouts.app')
@section('title', isset($coupon) ? 'Edit: ' . $coupon->code : 'Add Coupon')
@section('content')

    @php
        $isEdit = isset($coupon);
        $r = $isEdit ? $coupon->rules : null;
    @endphp

    <style>
        .fw {
            max-width: 860px
        }

        .pc {
            background: white;
            border: 1px solid #eef2f6;
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 14px
        }

        .ph {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 20px;
            border-bottom: 1px solid #eef2f6
        }

        .pt {
            font-family: 'Cinzel', serif;
            font-size: 12px;
            font-weight: 700;
            color: #00285a;
            letter-spacing: 1px
        }

        .sl {
            font-size: 10px;
            font-weight: 700;
            color: #7a8fa6;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: block;
            padding: 14px 20px 0
        }

        .fg {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            padding: 14px 20px
        }

        .fgrp {
            display: flex;
            flex-direction: column;
            gap: 5px
        }

        .fgrp.full {
            grid-column: 1/-1
        }

        .fgrp label {
            font-size: 11px;
            font-weight: 700;
            color: #7a8fa6;
            text-transform: uppercase;
            letter-spacing: .5px
        }

        .fc {
            padding: 10px 14px;
            border: 1.5px solid #e8edf5;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: .15s;
            width: 100%;
            background: white
        }

        .fc:focus {
            border-color: #00285a
        }

        /* Custom Multi-Select Styles */
        .multi-select-wrapper {
            border: 1.5px solid #e8edf5;
            border-radius: 8px;
            background: white;
            overflow: hidden;
        }
        
        .multi-select-wrapper:focus-within {
            border-color: #00285a;
        }
        
        .multi-select-search {
            width: 100%;
            padding: 10px 12px;
            border: none;
            border-bottom: 1px solid #e8edf5;
            font-size: 13px;
            outline: none;
            background: white;
        }
        
        .multi-select-search:focus {
            outline: none;
        }
        
        .multi-select-options {
            max-height: 250px;
            overflow-y: auto;
            padding: 5px 0;
        }
        
        .multi-select-option {
            padding: 8px 12px;
            cursor: pointer;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
            user-select: none;
        }
        
        .multi-select-option:hover {
            background: #f8fafc;
        }
        
        .multi-select-option.selected {
            background: #00285a;
            color: white;
        }
        
        .multi-select-option.selected .option-check {
            color: #ffd700;
        }
        
        .option-check {
            width: 16px;
            font-size: 14px;
            display: inline-block;
        }
        
        .option-text {
            flex: 1;
            font-size: 13px;
        }
        
        .selection-buttons {
            display: flex;
            gap: 8px;
            padding: 8px 12px;
            border-top: 1px solid #e8edf5;
            background: #fafbff;
        }
        
        .selection-btn {
            padding: 5px 12px;
            font-size: 11px;
            background: white;
            border: 1px solid #e8edf5;
            border-radius: 6px;
            cursor: pointer;
            transition: .15s;
        }
        
        .selection-btn:hover {
            background: #00285a;
            color: white;
            border-color: #00285a;
        }
        
        .selected-items-info {
            background: #f8fafc;
            border-radius: 8px;
            padding: 10px;
            margin-top: 10px;
            font-size: 12px;
            border: 1px solid #e8edf5;
        }

        .selected-items-info strong {
            color: #00285a;
            display: block;
            margin-bottom: 8px;
        }

        .selected-tag {
            display: inline-block;
            background: #e8edf5;
            color: #00285a;
            padding: 4px 10px;
            border-radius: 20px;
            margin: 3px;
            font-size: 11px;
            font-weight: 500;
        }
        
        .selected-tag-remove {
            margin-left: 5px;
            cursor: pointer;
            font-weight: bold;
            opacity: 0.6;
        }
        
        .selected-tag-remove:hover {
            opacity: 1;
            color: #ff3f6c;
        }

        .hint {
            font-size: 11px;
            color: #7a8fa6;
            margin-top: 8px;
        }

        .ferr {
            font-size: 11px;
            color: #ff3f6c;
            margin-top: 3px
        }

        .tog {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 14px 20px
        }

        .tog input {
            width: 17px;
            height: 17px;
            accent-color: #00285a;
            cursor: pointer
        }

        .tog span {
            font-size: 13px;
            font-weight: 600;
            color: #333
        }

        .fa {
            display: flex;
            gap: 10px;
            padding: 16px 20px;
            border-top: 1px solid #eef2f6;
            background: #fafbff
        }

        .bs {
            background: #00285a;
            color: white;
            border: none;
            padding: 10px 26px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit
        }

        .bs:hover {
            background: #1e3f75
        }

        .bc {
            background: white;
            color: #555;
            border: 1px solid #e8edf5;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none
        }

        .ebox {
            background: #fce4ec;
            color: #c62828;
            padding: 12px 16px;
            margin: 14px 20px 0;
            border-radius: 10px;
            font-size: 13px
        }

        .preview-box {
            background: #00285a;
            border-radius: 14px;
            padding: 20px 24px;
            margin: 14px 20px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px
        }

        .preview-code {
            font-size: 28px;
            font-weight: 700;
            font-family: 'Cinzel', serif;
            color: #ffd700;
            letter-spacing: 3px
        }

        .preview-info {
            text-align: right;
            font-size: 13px;
            opacity: .8
        }

        @media(max-width:640px) {
            .fg {
                grid-template-columns: 1fr
            }

            .fgrp.full {
                grid-column: 1
            }
        }
    </style>

    <div class="fw">

        {{-- LIVE PREVIEW --}}
        <div class="preview-box" id="previewBox">
            <div>
                <div style="font-size:11px;opacity:.6;margin-bottom:4px;letter-spacing:1px">COUPON CODE</div>
                <div class="preview-code" id="previewCode">{{ $isEdit ? $coupon->code : 'CODE' }}</div>
                <div style="font-size:12px;opacity:.7;margin-top:4px" id="previewDesc">
                    {{ $isEdit ? $coupon->description : 'Description' }}</div>
            </div>
            <div class="preview-info">
                <div style="font-size:22px;font-weight:700" id="previewVal">
                    @if ($isEdit)
                        {{ $coupon->type === 'percent' ? $coupon->value . '% Off' : '₹' . number_format($coupon->value) . ' Off' }}
                    @else
                        — Off
                    @endif
                </div>
                <div id="previewMin" style="margin-top:4px">
                    @if ($r && $r->min_order_amount > 0)
                        Min order ₹{{ number_format($r->min_order_amount) }}
                    @endif
                </div>
                <div id="previewExpiry" style="margin-top:2px">
                    @if ($r && $r->valid_until)
                        Expires {{ \Carbon\Carbon::parse($r->valid_until)->format('d M Y') }}
                    @endif
                </div>
            </div>
        </div>

        <form method="POST" action="{{ $isEdit ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}"
            id="couponForm">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            {{-- BASIC --}}
            <div class="pc">
                <div class="ph">
                    <div class="pt">{{ $isEdit ? 'EDIT COUPON' : 'NEW COUPON' }}</div>
                    <a href="{{ route('admin.coupons.index') }}" class="bc" style="font-size:11px;padding:6px 12px"><i
                            class="bi bi-arrow-left"></i> Back</a>
                </div>

                @if ($errors->any())
                    <div class="ebox">
                        @foreach ($errors->all() as $e)
                            <div>• {{ $e }}</div>
                        @endforeach
                    </div>
                @endif

                <span class="sl">Coupon Details</span>
                <div class="fg">
                    <div class="fgrp">
                        <label>Coupon Code *</label>
                        <input class="fc" type="text" name="code" id="codeInput"
                            value="{{ old('code', $isEdit ? $coupon->code : '') }}" required placeholder="e.g. WELCOME10"
                            style="text-transform:uppercase;font-weight:700;font-size:15px;letter-spacing:2px">
                        <div class="hint">Capital letters mein rakho — customer yahi type karega</div>
                        @error('code')
                            <div class="ferr">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="fgrp">
                        <label>Discount Type *</label>
                        <select class="fc" name="type" id="typeInput" onchange="updatePreview()">
                            <option value="percent"
                                {{ old('type', $isEdit ? $coupon->type : '') === 'percent' ? 'selected' : '' }}>Percent Off
                                (%)</option>
                            <option value="flat"
                                {{ old('type', $isEdit ? $coupon->type : '') === 'flat' ? 'selected' : '' }}>Flat Off
                                (₹)</option>
                        </select>
                    </div>
                    <div class="fgrp">
                        <label>Discount Value *</label>
                        <input class="fc" type="number" name="value" id="valueInput"
                            value="{{ old('value', $isEdit ? $coupon->value : '') }}" min="0" step="0.01"
                            required placeholder="10" oninput="updatePreview()">
                        <div class="hint" id="valueHint">e.g. 10 = 10% off on cart</div>
                        @error('value')
                            <div class="ferr">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="fgrp">
                        <label>Description</label>
                        <input class="fc" type="text" name="description" id="descInput"
                            value="{{ old('description', $isEdit ? $coupon->description : '') }}"
                            placeholder="e.g. New user pe 10% off"
                            oninput="document.getElementById('previewDesc').textContent=this.value">
                    </div>
                </div>

                <label class="tog">
                    <input type="checkbox" name="is_active" value="1"
                        {{ old('is_active', $isEdit ? $coupon->is_active : true) ? 'checked' : '' }}>
                    <span>Coupon Active</span>
                </label>
            </div>

            {{-- RULES --}}
            <div class="pc">
                <div class="ph">
                    <div class="pt">CONDITIONS & LIMITS</div>
                </div>
                <div class="fg">
                    <div class="fgrp">
                        <label>Min Order Amount (₹)</label>
                        <input class="fc" type="number" name="min_order_amount"
                            value="{{ old('min_order_amount', $r ? $r->min_order_amount : '') }}" min="0"
                            step="0.01" placeholder="999"
                            oninput="document.getElementById('previewMin').textContent=this.value?'Min order ₹'+Number(this.value).toLocaleString('en-IN'):''">
                        <div class="hint">Is amount se kam pe coupon nahi lagega. 0 = no minimum</div>
                    </div>
                    <div class="fgrp">
                        <label>Max Discount Cap (₹)</label>
                        <input class="fc" type="number" name="max_discount_amount"
                            value="{{ old('max_discount_amount', $r ? $r->max_discount_amount : '') }}" min="0"
                            step="0.01" placeholder="300">
                        <div class="hint">Percent coupons ke liye max discount limit. Leave blank = no cap</div>
                    </div>
                    <div class="fgrp">
                        <label>Total Usage Limit</label>
                        <input class="fc" type="number" name="usage_limit_total"
                            value="{{ old('usage_limit_total', $r ? $r->usage_limit_total : '') }}" min="1"
                            placeholder="100">
                        <div class="hint">Total kitni baar use ho sakta hai. Leave blank = unlimited</div>
                    </div>
                    <div class="fgrp">
                        <label>Per User Limit</label>
                        <input class="fc" type="number" name="usage_limit_per_user"
                            value="{{ old('usage_limit_per_user', $r ? $r->usage_limit_per_user : 1) }}" min="1"
                            placeholder="1">
                        <div class="hint">Ek user kitni baar use kar sakta hai</div>
                    </div>
                    <div class="fgrp">
                        <label>Valid From</label>
                        <input class="fc" type="datetime-local" name="valid_from"
                            value="{{ old('valid_from', $r && $r->valid_from ? \Carbon\Carbon::parse($r->valid_from)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}">
                    </div>
                    <div class="fgrp">
                        <label>Valid Until (Expiry)</label>
                        <input class="fc" type="datetime-local" name="valid_until"
                            value="{{ old('valid_until', $r && $r->valid_until ? \Carbon\Carbon::parse($r->valid_until)->format('Y-m-d\TH:i') : '') }}"
                            oninput="updateExpiry(this.value)">
                        <div class="hint">Leave blank = no expiry</div>
                    </div>
                </div>
            </div>

            {{-- PRODUCT & CATEGORY RESTRICTIONS --}}
            <div class="pc">
                <div class="ph">
                    <div class="pt">PRODUCT & CATEGORY RESTRICTIONS</div>
                </div>
                <div class="fg">
                    <div class="fgrp full">
                        <label>Specific Products (Optional)</label>
                        <div id="productMultiSelect"></div>
                        <input type="hidden" name="products[]" id="selectedProductsInput" value="">
                        <div class="hint">
                            <i class="bi bi-mouse"></i> Click on items to select/deselect (No need to press Ctrl)
                        </div>
                        <div id="selectedProductsInfo" class="selected-items-info" style="display: none">
                            <strong>Selected Products (<span id="selectedProductsCount">0</span>):</strong>
                            <div id="selectedProductsList"></div>
                        </div>
                    </div>

                    <div class="fgrp full">
                        <label>Specific Categories (Optional)</label>
                        <div id="categoryMultiSelect"></div>
                        <input type="hidden" name="categories[]" id="selectedCategoriesInput" value="">
                        <div class="hint">
                            <i class="bi bi-mouse"></i> Click on items to select/deselect (No need to press Ctrl)
                        </div>
                        <div id="selectedCategoriesInfo" class="selected-items-info" style="display: none">
                            <strong>Selected Categories (<span id="selectedCategoriesCount">0</span>):</strong>
                            <div id="selectedCategoriesList"></div>
                        </div>
                    </div>

                    <div class="fgrp full">
                        <div class="hint" style="color:#00285a; background:#e8edf5; padding:10px; border-radius:8px;">
                            <strong><i class="bi bi-lightbulb"></i> Note:</strong>
                            If you select products or categories, the coupon will ONLY apply to those items.
                            If both are selected, it will apply to products that match EITHER the selected products OR
                            selected categories.
                        </div>
                    </div>
                </div>
            </div>

            <div class="pc">
                <div class="fa">
                    <button type="submit" class="bs"><i class="bi bi-check-lg"></i>
                        {{ $isEdit ? 'Update Coupon' : 'Save Coupon' }}</button>
                    <a href="{{ route('admin.coupons.index') }}" class="bc">Cancel</a>
                </div>
            </div>

            {{-- USAGE HISTORY (edit only) --}}
            @if ($isEdit && $coupon->usage->count() > 0)
                <div class="pc">
                    <div class="ph">
                        <div class="pt">USAGE HISTORY</div>
                        <span style="font-size:12px;color:#7a8fa6">{{ $coupon->usage->count() }} times used ·
                            ₹{{ number_format($coupon->usage->sum('discount_applied')) }} total discount given</span>
                    </div>
                    <table style="width:100%;border-collapse:collapse">
                        <thead>
                            <tr>
                                <th
                                    style="padding:9px 14px;font-size:10px;font-weight:700;color:#7a8fa6;text-transform:uppercase;background:#f8fafc;border-bottom:1px solid #eef2f6;text-align:left">
                                    Order #</th>
                                <th
                                    style="padding:9px 14px;font-size:10px;font-weight:700;color:#7a8fa6;text-transform:uppercase;background:#f8fafc;border-bottom:1px solid #eef2f6;text-align:left">
                                    Customer</th>
                                <th
                                    style="padding:9px 14px;font-size:10px;font-weight:700;color:#7a8fa6;text-transform:uppercase;background:#f8fafc;border-bottom:1px solid #eef2f6;text-align:left">
                                    Discount</th>
                                <th
                                    style="padding:9px 14px;font-size:10px;font-weight:700;color:#7a8fa6;text-transform:uppercase;background:#f8fafc;border-bottom:1px solid #eef2f6;text-align:left">
                                    Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($coupon->usage->take(10) as $usage)
                                <tr>
                                    <td style="padding:10px 14px;font-size:13px;border-bottom:1px solid rgba(0,0,0,.04)">
                                        <a href="{{ route('admin.orders.show', $usage->order_id) }}"
                                            style="color:#00285a;font-weight:600;text-decoration:none">
                                            {{ $usage->order->order_number ?? '#' . $usage->order_id }}
                                        </a>
                                    </td>
                                    <td
                                        style="padding:10px 14px;font-size:13px;border-bottom:1px solid rgba(0,0,0,.04);color:#555">
                                        {{ $usage->user->name ?? 'Guest' }}
                                    </td>
                                    <td
                                        style="padding:10px 14px;font-size:13px;border-bottom:1px solid rgba(0,0,0,.04);font-weight:700;color:#c44536">
                                        ₹{{ number_format($usage->discount_applied) }}
                                    </td>
                                    <td
                                        style="padding:10px 14px;font-size:11px;border-bottom:1px solid rgba(0,0,0,.04);color:#7a8fa6">
                                        {{ $usage->used_at->format('d M Y, h:i A') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </form>
    </div>

    @push('scripts')
        <script>
            // Products Data
            const productsData = @json($products->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price
                ];
            }));
            
            const categoriesData = @json($categories->map(function($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name
                ];
            }));
            
            // Pre-selected values for edit mode
            let selectedProducts = @json($isEdit ? $coupon->products->pluck('id') : []);
            let selectedCategories = @json($isEdit ? $coupon->categories->pluck('id') : []);
            
            // Custom Multi-Select Class
            class CustomMultiSelect {
                constructor(containerId, items, selectedItems, onUpdate, placeholder) {
                    this.containerId = containerId;
                    this.items = items;
                    this.selectedItems = new Set(selectedItems);
                    this.onUpdate = onUpdate;
                    this.placeholder = placeholder;
                    this.filterText = '';
                    this.init();
                }
                
                init() {
                    this.render();
                }
                
                render() {
                    const container = document.getElementById(this.containerId);
                    if (!container) return;
                    
                    const filteredItems = this.items.filter(item => 
                        item.name.toLowerCase().includes(this.filterText.toLowerCase())
                    );
                    
                    container.innerHTML = `
                        <div class="multi-select-wrapper">
                            <input type="text" class="multi-select-search" placeholder="🔍 ${this.placeholder}" 
                                   id="${this.containerId}-search" autocomplete="off">
                            <div class="multi-select-options" id="${this.containerId}-options">
                                ${filteredItems.map(item => `
                                    <div class="multi-select-option ${this.selectedItems.has(item.id) ? 'selected' : ''}" 
                                         data-id="${item.id}" data-name="${item.name}">
                                        <span class="option-check">${this.selectedItems.has(item.id) ? '✓' : '◻'}</span>
                                        <span class="option-text">${this.escapeHtml(item.name)} ${item.price ? '(₹' + item.price.toLocaleString('en-IN') + ')' : ''}</span>
                                    </div>
                                `).join('')}
                                ${filteredItems.length === 0 ? '<div style="padding: 20px; text-align: center; color: #999;">No items found</div>' : ''}
                            </div>
                            <div class="selection-buttons">
                                <button type="button" class="selection-btn" onclick="this.closest('.multi-select-wrapper').__selectAll?.()">
                                    <i class="bi bi-check-all"></i> Select All
                                </button>
                                <button type="button" class="selection-btn" onclick="this.closest('.multi-select-wrapper').__clearAll?.()">
                                    <i class="bi bi-x-circle"></i> Clear All
                                </button>
                            </div>
                        </div>
                    `;
                    
                    // Attach event listeners
                    const searchInput = document.getElementById(`${this.containerId}-search`);
                    const optionsContainer = document.getElementById(`${this.containerId}-options`);
                    
                    if (searchInput) {
                        searchInput.addEventListener('input', (e) => {
                            this.filterText = e.target.value;
                            this.render();
                        });
                    }
                    
                    if (optionsContainer) {
                        optionsContainer.querySelectorAll('.multi-select-option').forEach(option => {
                            option.addEventListener('click', (e) => {
                                const id = parseInt(option.dataset.id);
                                if (this.selectedItems.has(id)) {
                                    this.selectedItems.delete(id);
                                } else {
                                    this.selectedItems.add(id);
                                }
                                this.render();
                                if (this.onUpdate) this.onUpdate(Array.from(this.selectedItems));
                            });
                        });
                    }
                    
                    // Attach helper functions to wrapper
                    const wrapper = container.querySelector('.multi-select-wrapper');
                    if (wrapper) {
                        wrapper.__selectAll = () => {
                            const visibleItems = this.items.filter(item => 
                                item.name.toLowerCase().includes(this.filterText.toLowerCase())
                            );
                            visibleItems.forEach(item => this.selectedItems.add(item.id));
                            this.render();
                            if (this.onUpdate) this.onUpdate(Array.from(this.selectedItems));
                        };
                        
                        wrapper.__clearAll = () => {
                            const visibleItems = this.items.filter(item => 
                                item.name.toLowerCase().includes(this.filterText.toLowerCase())
                            );
                            visibleItems.forEach(item => this.selectedItems.delete(item.id));
                            this.render();
                            if (this.onUpdate) this.onUpdate(Array.from(this.selectedItems));
                        };
                    }
                }
                
                escapeHtml(text) {
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                }
                
                getSelectedIds() {
                    return Array.from(this.selectedItems);
                }
            }
            
            // Update selected products display
           
            
            // Update selected categories display
          // Replace the updateSelectedProductsDisplay and updateSelectedCategoriesDisplay functions

// Update selected products display
function updateSelectedProductsDisplay() {
    const selectedIds = productMultiSelect?.getSelectedIds() || [];
    const infoDiv = document.getElementById('selectedProductsInfo');
    const listDiv = document.getElementById('selectedProductsList');
    const countSpan = document.getElementById('selectedProductsCount');
    const input = document.getElementById('selectedProductsInput');
    
    // CRITICAL FIX: Update hidden input with array format
    if (input) {
        // Set the value as a comma-separated string for form submission
        input.value = selectedIds.join(',');
        
        // Also set the name attribute to send as array
        input.name = 'products[]';
    }
    
    if (selectedIds.length > 0) {
        const selectedNames = selectedIds.map(id => {
            const product = productsData.find(p => p.id === id);
            return product ? product.name : '';
        }).filter(name => name);
        
        listDiv.innerHTML = selectedNames.map((name, index) => 
            `<span class="selected-tag">${escapeHtml(name)} <span class="selected-tag-remove" onclick="removeProduct(${selectedIds[index]})">×</span></span>`
        ).join('');
        countSpan.textContent = selectedIds.length;
        infoDiv.style.display = 'block';
    } else {
        infoDiv.style.display = 'none';
    }
}

// Update selected categories display
function updateSelectedCategoriesDisplay() {
    const selectedIds = categoryMultiSelect?.getSelectedIds() || [];
    const infoDiv = document.getElementById('selectedCategoriesInfo');
    const listDiv = document.getElementById('selectedCategoriesList');
    const countSpan = document.getElementById('selectedCategoriesCount');
    const input = document.getElementById('selectedCategoriesInput');
    
    // CRITICAL FIX: Update hidden input with array format
    if (input) {
        // Set the value as a comma-separated string for form submission
        input.value = selectedIds.join(',');
        
        // Also set the name attribute to send as array
        input.name = 'categories[]';
    }
    
    if (selectedIds.length > 0) {
        const selectedNames = selectedIds.map(id => {
            const category = categoriesData.find(c => c.id === id);
            return category ? category.name : '';
        }).filter(name => name);
        
        listDiv.innerHTML = selectedNames.map((name, index) => 
            `<span class="selected-tag">${escapeHtml(name)} <span class="selected-tag-remove" onclick="removeCategory(${selectedIds[index]})">×</span></span>`
        ).join('');
        countSpan.textContent = selectedIds.length;
        infoDiv.style.display = 'block';
    } else {
        infoDiv.style.display = 'none';
    }
}
            
            // Remove product from selection
            function removeProduct(productId) {
                if (productMultiSelect) {
                    productMultiSelect.selectedItems.delete(productId);
                    productMultiSelect.render();
                    updateSelectedProductsDisplay();
                }
            }
            
            // Remove category from selection
            function removeCategory(categoryId) {
                if (categoryMultiSelect) {
                    categoryMultiSelect.selectedItems.delete(categoryId);
                    categoryMultiSelect.render();
                    updateSelectedCategoriesDisplay();
                }
            }
            
            // Escape HTML
            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }
            
            // Update preview functions
            function updatePreview() {
                var code = document.getElementById('codeInput').value || 'CODE';
                var type = document.getElementById('typeInput').value;
                var val = document.getElementById('valueInput').value;
                var hint = document.getElementById('valueHint');

                document.getElementById('previewCode').textContent = code.toUpperCase();
                document.getElementById('previewVal').textContent = val ?
                    (type === 'percent' ? val + '% Off' : '₹' + Number(val).toLocaleString('en-IN') + ' Off') :
                    '— Off';

                hint.textContent = type === 'percent' ?
                    'e.g. 10 = 10% off on cart total' :
                    'e.g. 200 = ₹200 flat off on cart';
            }

            function updateExpiry(val) {
                var el = document.getElementById('previewExpiry');
                if (val) {
                    var d = new Date(val);
                    el.textContent = 'Expires ' + d.toLocaleDateString('en-IN', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    });
                } else {
                    el.textContent = '';
                }
            }
            
            // Initialize multi-selects
            let productMultiSelect, categoryMultiSelect;
            
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize product multi-select
                productMultiSelect = new CustomMultiSelect(
                    'productMultiSelect',
                    productsData,
                    selectedProducts,
                    () => updateSelectedProductsDisplay(),
                    'Search products...'
                );
                
                // Initialize category multi-select
                categoryMultiSelect = new CustomMultiSelect(
                    'categoryMultiSelect',
                    categoriesData,
                    selectedCategories,
                    () => updateSelectedCategoriesDisplay(),
                    'Search categories...'
                );
                
                // Update displays
                updateSelectedProductsDisplay();
                updateSelectedCategoriesDisplay();
                updatePreview();
                
                // Code input uppercase
                var codeInput = document.getElementById('codeInput');
                if (codeInput) {
                    codeInput.addEventListener('input', function() {
                        this.value = this.value.toUpperCase();
                        updatePreview();
                    });
                }
            });
            
            // Make functions globally available
            window.removeProduct = removeProduct;
            window.removeCategory = removeCategory;
            window.updatePreview = updatePreview;
            window.updateExpiry = updateExpiry;
        </script>
    @endpush
@endsection