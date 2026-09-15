{{-- resources/views/admin/customers/show.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Customer: ' . $user->name)
@section('content')

@php
    $phone = $user->phone;
    $cleanPhone = $phone ? preg_replace('/[^0-9]/', '', $phone) : '';
    if ($cleanPhone && strlen($cleanPhone) === 10) {
        $cleanPhone = '91' . $cleanPhone;
    }
    $latestLog = $visitorLogs->first();
    $waText = "Hi " . $user->name . "! Thank you for visiting Vayu. Can we assist you with any questions or styling guidance?";
@endphp

<div class="customer-show-grid">
    
    {{-- Left: Customer Profile Card & Quick Outreach Actions --}}
    <div class="customer-sidebar-col">
        <div class="admin-card">
            <div class="customer-profile-card">
                <div class="cust-avatar">
                    {{ strtoupper(substr($user->name ?? 'C', 0, 1)) }}
                </div>
                <h3 class="cust-name">{{ $user->name }}</h3>
                <div class="cust-email">{{ $user->email }}</div>

                {{-- Direct WhatsApp & Email Outreach Buttons --}}
                <div class="cust-outreach-actions">
                    @if($cleanPhone)
                        <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode($waText) }}" 
                           target="_blank" 
                           class="btn-admin btn-whatsapp-cust" 
                           title="Chat on WhatsApp">
                            <i class="bi bi-whatsapp"></i> WhatsApp Chat
                        </a>
                    @endif

                    @if($user->email)
                        <button type="button" 
                                class="btn-admin btn-email-cust" 
                                onclick="openCustEmailModal()">
                            <i class="bi bi-envelope-fill"></i> Send Notification
                        </button>
                    @endif
                </div>

                <div class="cust-details-list">
                    <div class="cust-detail-row">
                        <span>Phone</span><strong>{{ $user->phone ?? '-' }}</strong>
                    </div>
                    <div class="cust-detail-row">
                        <span>City / State</span><strong>{{ $user->city ?? ($latestLog?->city ?? '-') }}, {{ $user->state ?? ($latestLog?->state ?? '') }}</strong>
                    </div>
                    <div class="cust-detail-row">
                        <span>Total Orders</span><strong>{{ $user->orders->count() }}</strong>
                    </div>
                    <div class="cust-detail-row">
                        <span>Total Spent</span><strong class="text-danger">&#8377;{{ number_format($totalSpent) }}</strong>
                    </div>
                    <div class="cust-detail-row">
                        <span>Joined</span><strong>{{ $user->created_at->format('d M Y') }}</strong>
                    </div>
                    @if($latestLog)
                        <div class="cust-detail-row">
                            <span>Phone Model</span><strong>{{ $latestLog->device_brand }} {{ $latestLog->device_model ? '(' . $latestLog->device_model . ')' : '' }}</strong>
                        </div>
                        <div class="cust-detail-row">
                            <span>Traffic Source</span><span class="badge-source-sm">{{ $latestLog->source ?: 'Direct' }}</span>
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('admin.customers.toggle', $user) }}" style="margin-top:16px">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-admin {{ $user->is_active ? 'btn-danger-soft' : 'btn-navy' }}" style="width:100%">
                        <i class="bi bi-{{ $user->is_active ? 'lock' : 'unlock' }}"></i>
                        {{ $user->is_active ? 'Block Customer' : 'Unblock Customer' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Right: Tabs (Order History & Live Activity Journey) --}}
    <div class="customer-main-col">
        
        {{-- Section 1: Live Customer Activity Journey Timeline --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="bi bi-activity text-primary"></i> Customer Live Activity Timeline
                </div>
                <span class="badge-pill-soft">{{ $activities->count() }} Recent Events</span>
            </div>

            <div class="cust-activity-timeline">
                @forelse($activities as $act)
                    <div class="timeline-event-row">
                        <div class="timeline-dot-wrap">
                            <div class="timeline-icon-dot {{ $act->event_badge_class }}">
                                <i class="bi {{ $act->event_icon }}"></i>
                            </div>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-header-flex">
                                <strong class="timeline-title">{{ $act->event_title }}</strong>
                                <small class="timeline-time">{{ $act->created_at->diffForHumans() }} ({{ $act->created_at->format('h:i A') }})</small>
                            </div>
                            <div class="timeline-meta-flex">
                                <span class="badge-event-sm {{ $act->event_badge_class }}">{{ $act->event_label }}</span>
                                <span class="meta-tag"><i class="bi bi-geo-alt"></i> {{ $act->city ?: 'India' }}</span>
                                <span class="meta-tag"><i class="bi bi-phone"></i> {{ $act->device_brand }} {{ $act->device_model }}</span>
                                <span class="meta-tag"><i class="bi bi-share"></i> {{ $act->source }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state-card">
                        <i class="bi bi-clock-history text-muted" style="font-size:24px;"></i>
                        <p>No recent activity events recorded for this customer.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Section 2: Order History --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title"><i class="bi bi-bag-check"></i> ORDER HISTORY</div>
            </div>
            <div class="table-responsive-admin">
                <table class="table-admin">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->orders as $order)
                            @php $sm=['pending'=>'warning','confirmed'=>'info','shipped'=>'purple','delivered'=>'success','cancelled'=>'danger']; @endphp
                            <tr>
                                <td><strong style="color:#00285a">{{ $order->order_number }}</strong></td>
                                <td style="font-size:12px;color:#7a8fa6">{{ $order->created_at->format('d M Y') }}</td>
                                <td><strong style="color:#c44536">&#8377;{{ number_format($order->total_amount) }}</strong></td>
                                <td><span class="badge-pill badge-{{ $order->payment_status==='paid'?'success':'warning' }}">{{ ucfirst($order->payment_status) }}</span></td>
                                <td><span class="badge-pill badge-{{ $sm[$order->status]??'gray' }}">{{ ucfirst($order->status) }}</span></td>
                                <td><a href="{{ route('admin.orders.show',$order) }}" class="btn-admin btn-light btn-sm btn-icon"><i class="bi bi-eye"></i></a></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" style="text-align:center;padding:30px;color:#7a8fa6">No orders yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

{{-- ── Email Outreach Modal ── --}}
<div class="admin-modal-backdrop" id="custEmailModal" style="display:none;">
    <div class="admin-modal-card">
        <div class="admin-modal-header">
            <h3><i class="bi bi-envelope-paper-fill text-primary"></i> Send Direct Message to {{ $user->name }}</h3>
            <button type="button" onclick="closeCustEmailModal()" class="close-btn">&times;</button>
        </div>
        <form id="custEmailForm" onsubmit="handleSendCustEmail(event)">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <input type="hidden" name="channel" value="email">
            <input type="hidden" name="email" value="{{ $user->email }}">

            <div class="admin-modal-body">
                <div class="form-grp mb-3">
                    <label>Recipient:</label>
                    <input type="text" value="{{ $user->name }} ({{ $user->email }})" class="form-control-admin" readonly style="background:#f8fafc;font-weight:700;">
                </div>

                <div class="form-grp mb-3">
                    <label>Email Subject:</label>
                    <input type="text" name="subject" id="custEmailSubject" class="form-control-admin" required value="Special Update from Vayu">
                </div>

                <div class="form-grp mb-3">
                    <label>Message Content:</label>
                    <textarea name="message" id="custEmailMessage" rows="5" class="form-control-admin" required style="resize:vertical;">Hi {{ $user->name }},&#10;&#10;Thank you for being a valued customer at Vayu. We'd love to assist you with our latest drops or answer any styling queries!&#10;&#10;Best regards,&#10;Vayu Team</textarea>
                </div>

                <div id="custEmailStatus" style="display:none;font-size:12.5px;font-weight:700;margin-top:8px;"></div>
            </div>

            <div class="admin-modal-footer">
                <button type="button" class="btn-admin btn-light" onclick="closeCustEmailModal()">Cancel</button>
                <button type="submit" class="btn-admin btn-navy" id="custEmailBtn">
                    <i class="bi bi-send-fill"></i> Send Email
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openCustEmailModal() {
    document.getElementById('custEmailStatus').style.display = 'none';
    document.getElementById('custEmailModal').style.display = 'flex';
}

function closeCustEmailModal() {
    document.getElementById('custEmailModal').style.display = 'none';
}

function handleSendCustEmail(e) {
    e.preventDefault();
    var form = document.getElementById('custEmailForm');
    var btn = document.getElementById('custEmailBtn');
    var status = document.getElementById('custEmailStatus');
    var origHtml = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Sending...';
    status.style.display = 'none';

    var formData = new FormData(form);

    fetch('{{ route('admin.analytics.notify') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        btn.disabled = false;
        btn.innerHTML = origHtml;
        status.style.display = 'block';

        if (data.success) {
            status.style.color = '#059669';
            status.innerHTML = '<i class="bi bi-check-circle-fill"></i> ' + data.message;
            setTimeout(function() {
                closeCustEmailModal();
            }, 1200);
        } else {
            status.style.color = '#dc2626';
            status.innerHTML = '<i class="bi bi-exclamation-circle-fill"></i> ' + (data.message || 'Error sending email');
        }
    })
    .catch(function() {
        btn.disabled = false;
        btn.innerHTML = origHtml;
        status.style.display = 'block';
        status.style.color = '#dc2626';
        status.innerHTML = '<i class="bi bi-exclamation-circle-fill"></i> Network error.';
    });
}
</script>

<style>
.customer-show-grid {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 20px;
    align-items: flex-start;
}

@media (max-width: 992px) {
    .customer-show-grid {
        grid-template-columns: 1fr;
    }
}

.customer-profile-card {
    padding: 24px 20px;
    text-align: center;
}

.cust-avatar {
    width: 68px;
    height: 68px;
    border-radius: 50%;
    background: linear-gradient(135deg, #00285a, #1e3f75);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 26px;
    font-weight: 800;
    margin: 0 auto 12px;
}

.cust-name {
    font-weight: 800;
    font-size: 16px;
    color: #00285a;
    margin: 0 0 3px;
}

.cust-email {
    font-size: 12.5px;
    color: #64748b;
    margin-bottom: 16px;
}

.cust-outreach-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 18px;
}

.btn-whatsapp-cust {
    background: #25d366;
    color: #ffffff;
    width: 100%;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(37, 211, 102, 0.25);
}

.btn-whatsapp-cust:hover {
    background: #1ebc59;
    color: #ffffff;
}

.btn-email-cust {
    background: #00285a;
    color: #ffffff;
    width: 100%;
    justify-content: center;
}

.btn-email-cust:hover {
    background: #1e3f75;
    color: #ffffff;
}

.cust-details-list {
    display: flex;
    flex-direction: column;
    text-align: left;
    border-top: 1px solid #f1f5f9;
}

.cust-detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 12.5px;
    padding: 9px 0;
    border-bottom: 1px solid #f1f5f9;
}

.cust-detail-row span {
    color: #7a8fa6;
}

.badge-source-sm {
    background: #eff6ff;
    color: #1e40af;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 4px;
}

.btn-danger-soft {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.badge-pill-soft {
    background: #f1f5f9;
    color: #64748b;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 999px;
}

/* ─── Timeline Styles ─── */
.cust-activity-timeline {
    padding: 16px 20px;
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.timeline-event-row {
    display: flex;
    gap: 14px;
    align-items: flex-start;
    padding-bottom: 12px;
    border-bottom: 1px solid #f1f5f9;
}

.timeline-icon-dot {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    flex-shrink: 0;
}

.timeline-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.timeline-header-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.timeline-title {
    font-size: 13px;
    color: #00285a;
}

.timeline-time {
    font-size: 11px;
    color: #94a3b8;
}

.timeline-meta-flex {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.badge-event-sm {
    font-size: 10px;
    font-weight: 800;
    padding: 2px 6px;
    border-radius: 4px;
    text-transform: uppercase;
}

.meta-tag {
    font-size: 11px;
    color: #64748b;
    background: #f8fafc;
    padding: 1px 6px;
    border-radius: 4px;
    border: 1px solid #eef2f6;
}

.empty-state-card {
    padding: 30px 10px;
    text-align: center;
    color: #94a3b8;
    font-size: 13px;
}
</style>
@endsection
