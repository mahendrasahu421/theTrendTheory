{{-- resources/views/admin/reviews/index.blade.php --}}
@extends('admin.layouts.app')
@section('title','Reviews')
@section('content')

<style>
.card{background:white;border:1px solid #eef2f6;border-radius:14px;overflow:hidden}
.card-h{display:flex;justify-content:space-between;align-items:center;padding:14px 18px;border-bottom:1px solid #eef2f6}
.card-t{font-family:'Cinzel',serif;font-size:12px;font-weight:700;color:#00285a;letter-spacing:1px}
.dt{width:100%;border-collapse:collapse}
.dt th{padding:9px 14px;font-size:10px;font-weight:700;color:#7a8fa6;text-transform:uppercase;letter-spacing:.8px;background:#f8fafc;border-bottom:1px solid #eef2f6;text-align:left}
.dt td{padding:11px 14px;font-size:13px;border-bottom:1px solid rgba(0,0,0,.04);vertical-align:middle}
.dt tr:last-child td{border-bottom:none}
.dt tr:hover td{background:#fafbff}
.bdg{display:inline-flex;align-items:center;padding:2px 9px;border-radius:20px;font-size:11px;font-weight:700}
.bdg-g{background:#e8f5e9;color:#2e7d32}.bdg-r{background:#fce4ec;color:#c62828}
.bdg-a{background:#fff3e0;color:#e65100}
.stars{color:#ffd700;font-size:13px;letter-spacing:1px}
</style>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
    <div style="font-family:'Cinzel',serif;font-size:16px;font-weight:700;color:#00285a">Reviews</div>
</div>

@if(session('success'))
    <div style="background:#e8f5e9;color:#2e7d32;padding:10px 16px;border-radius:10px;margin-bottom:14px;font-size:13px;font-weight:600">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-h">
        <div class="card-t">All Reviews ({{ $reviews->total() }})</div>
    </div>
    <div style="overflow-x:auto">
        <table class="dt">
            <thead>
                <tr>
                    <th>Reviewer</th>
                    <th>Product</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Verified</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($reviews as $review)
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:13px;color:#00285a">{{ $review->reviewer_name }}</div>
                        @if($review->user)<div style="font-size:11px;color:#7a8fa6">{{ $review->user->email }}</div>@endif
                    </td>
                    <td style="font-size:12px;color:#555;max-width:150px">
                        {{ Str::limit($review->product->name ?? '—', 30) }}
                    </td>
                    <td>
                        <div class="stars">
                            @for($i=1;$i<=5;$i++)
                                {{ $i <= $review->rating ? '★' : '☆' }}
                            @endfor
                        </div>
                        <div style="font-size:11px;color:#7a8fa6">{{ $review->rating }}/5</div>
                    </td>
                    <td style="max-width:200px;font-size:12px;color:#555">
                        {{ Str::limit($review->comment, 80) }}
                    </td>
                    <td>
                        @if($review->is_verified)
                            <span class="bdg bdg-g"><i class="bi bi-patch-check-fill"></i>&nbsp;Verified</span>
                        @else
                            <span class="bdg" style="background:#f1f5f9;color:#64748b">Unverified</span>
                        @endif
                    </td>
                    <td>
                        <span class="bdg {{ $review->is_active ? 'bdg-g' : 'bdg-r' }}">
                            {{ $review->is_active ? 'Visible' : 'Hidden' }}
                        </span>
                    </td>
                    <td style="font-size:11px;color:#7a8fa6">{{ $review->created_at->format('d M Y') }}</td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <form method="POST" action="{{ route('admin.reviews.approve', $review) }}">
                                @csrf @method('PATCH')
                                <button type="submit" style="background:{{ $review->is_active ? '#fee2e2' : '#e8f5e9' }};color:{{ $review->is_active ? '#991b1b' : '#2e7d32' }};padding:5px 12px;border-radius:20px;font-size:11px;font-weight:700;border:none;cursor:pointer">
                                    {{ $review->is_active ? 'Hide' : 'Show' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Delete this review?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:#fee2e2;color:#991b1b;padding:5px 12px;border-radius:20px;font-size:11px;font-weight:700;border:none;cursor:pointer">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:40px;color:#7a8fa6">
                        <i class="bi bi-star" style="font-size:28px;display:block;margin-bottom:8px"></i>
                        No reviews yet
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($reviews->hasPages())
        <div style="padding:14px 18px;border-top:1px solid #eef2f6">{{ $reviews->links() }}</div>
    @endif
</div>
@endsection
