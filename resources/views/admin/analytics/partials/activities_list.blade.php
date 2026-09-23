@forelse($activities as $act)
    @php
        $u = $act->user;
        $phone = $u ? $u->phone : null;
        $cleanPhone = $phone ? preg_replace('/[^0-9]/', '', $phone) : '';
        if ($cleanPhone && strlen($cleanPhone) === 10) {
            $cleanPhone = '91' . $cleanPhone;
        }

        // Smart auto-draft message for WhatsApp
        $waText = "Hi " . ($u ? $u->name : 'there') . "! Welcome to THE TREND THEORY.";
        if ($act->event_type === 'checkout_started') {
            $waText = "Hi " . ($u ? $u->name : '') . "! We noticed you left items in your shopping bag at THE TREND THEORY. Complete your order today and get an EXTRA 10% OFF with code TREND10: " . url('/checkout');
        } elseif ($act->event_type === 'cart_added') {
            $waText = "Hi " . ($u ? $u->name : '') . "! Thank you for adding items to your bag at THE TREND THEORY. Need any help with size or fast delivery? We are here to help: " . url('/cart');
        } elseif ($act->event_type === 'product_viewed') {
            $waText = "Hi " . ($u ? $u->name : '') . "! We saw you checking out our streetwear drop at THE TREND THEORY. Let us know if you need any assistance with styling or sizing: " . ($act->url ?: url('/shop'));
        }
    @endphp

    <div class="stream-item-row {{ $act->event_type === 'checkout_started' ? 'highlight-abandoned' : '' }}">
        
        {{-- Col 1: Icon & Relative Time --}}
        <div class="stream-col-time">
            <div class="stream-icon-box {{ $act->event_badge_class }}">
                <i class="bi {{ $act->event_icon }}"></i>
            </div>
            <div class="stream-time-text">
                <strong>{{ $act->created_at->diffForHumans() }}</strong>
                <small>{{ $act->created_at->format('M d, h:i A') }}</small>
            </div>
        </div>

        {{-- Col 2: Event Details & Context Tags --}}
        <div class="stream-col-info">
            <div class="stream-title-line">
                <span class="event-type-badge {{ $act->event_badge_class }}">
                    {{ $act->event_label }}
                </span>
                <h4 class="event-title-text">{{ $act->event_title }}</h4>
            </div>

            {{-- Metadata Pills --}}
            <div class="stream-meta-pills">
                @if($u)
                    <span class="meta-tag tag-user">
                        <i class="bi bi-person-check-fill text-emerald"></i>
                        <b>{{ $u->name }}</b> ({{ $u->phone ?: $u->email }})
                    </span>
                @else
                    <span class="meta-tag tag-guest">
                        <i class="bi bi-person-circle"></i> Guest ({{ $act->ip_address }})
                    </span>
                @endif

                <span class="meta-tag">
                    <i class="bi bi-geo-alt-fill text-rose"></i> {{ $act->city ?: 'India' }}, {{ $act->state ?: '' }} 🇮🇳
                </span>

                <span class="meta-tag tag-source">
                    <i class="bi bi-share"></i> {{ $act->source ?: 'Direct' }}
                </span>

                <span class="meta-tag">
                    <i class="bi bi-phone text-indigo"></i> {{ $act->device_brand ?: 'Device' }} {{ $act->device_model ? '(' . $act->device_model . ')' : '' }}
                </span>
            </div>

            {{-- Event Data Preview (JSON Items) --}}
            @if(!empty($act->event_details))
                <div class="stream-details-box">
                    @if(isset($act->event_details['price']))
                        <span>Price: <b>₹{{ number_format($act->event_details['price']) }}</b></span>
                    @endif
                    @if(isset($act->event_details['size']) && $act->event_details['size'])
                        <span>Size: <b>{{ $act->event_details['size'] }}</b></span>
                    @endif
                    @if(isset($act->event_details['total']))
                        <span>Cart Total: <b class="text-emerald">₹{{ number_format($act->event_details['total']) }}</b></span>
                    @endif
                    @if(isset($act->event_details['items_count']))
                        <span>Items: <b>{{ $act->event_details['items_count'] }}</b></span>
                    @endif
                </div>
            @endif

            @if($act->contacted_at)
                <div class="contacted-note">
                    <i class="bi bi-check2-all text-emerald"></i> Contacted via <b>{{ strtoupper($act->contacted_channel) }}</b> {{ $act->contacted_at->diffForHumans() }}
                </div>
            @endif
        </div>

        {{-- Col 3: Direct Outreach Buttons --}}
        <div class="stream-col-actions">
            @if($cleanPhone)
                <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode($waText) }}" 
                   target="_blank" 
                   class="btn-outreach btn-whatsapp-outreach" 
                   title="Chat on WhatsApp">
                    <i class="bi bi-whatsapp"></i> WhatsApp
                </a>
            @else
                <button type="button" class="btn-outreach btn-disabled" disabled>
                    <i class="bi bi-whatsapp"></i> WhatsApp
                </button>
            @endif

            @if($u && $u->email)
                <button type="button" 
                        class="btn-outreach btn-email-outreach" 
                        onclick="openEmailModal('{{ $u->id }}', '{{ $u->name }}', '{{ $u->email }}', '{{ $act->id }}', '{{ addslashes($act->event_title) }}')">
                    <i class="bi bi-envelope-fill"></i> Send Email
                </button>
            @else
                <button type="button" class="btn-outreach btn-disabled" disabled>
                    <i class="bi bi-envelope"></i> Send Email
                </button>
            @endif
        </div>

    </div>
@empty
    <div class="empty-stream-state">
        <i class="bi bi-inbox text-muted" style="font-size:36px;display:block;margin-bottom:10px;"></i>
        <h4>No activity logs found</h4>
        <p class="text-muted">User activities like product views, additions to bag, and checkout drop-offs will appear here in real time.</p>
    </div>
@endforelse
