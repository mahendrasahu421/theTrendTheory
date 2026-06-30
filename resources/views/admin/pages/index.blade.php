@extends('admin.layouts.app')
@section('title', 'Static Pages')
@section('content')

<style>
.page-hdr { display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; }
.page-title { font-family:'Cinzel',serif; font-size:16px; font-weight:700; color:#00285a; letter-spacing:1px; }
.card { background:white; border:1px solid #eef2f6; border-radius:14px; overflow:hidden; }
.dt { width:100%; border-collapse:collapse; }
.dt th { padding:10px 14px; font-size:10px; font-weight:700; color:#7a8fa6; text-transform:uppercase; letter-spacing:.8px; background:#f8fafc; border-bottom:1px solid #eef2f6; text-align:left; }
.dt td { padding:12px 14px; font-size:13px; border-bottom:1px solid rgba(0,0,0,.04); vertical-align:middle; }
.dt tr:last-child td { border-bottom:none; }
.dt tr:hover td { background:#fafbff; }
.bdg { display:inline-flex; align-items:center; padding:2px 10px; border-radius:20px; font-size:11px; font-weight:700; }
.bdg-g { background:#e8f5e9; color:#2e7d32; }
.bdg-r { background:#fce4ec; color:#c62828; }
.btn-edit { background:#00285a; color:white; padding:5px 14px; border-radius:20px; font-size:11px; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:4px; }
.btn-edit:hover { background:#1e3f75; }
.btn-sm { padding:5px 12px; border-radius:6px; font-size:11px; font-weight:700; border:none; cursor:pointer; font-family:inherit; display:inline-flex; align-items:center; gap:4px; }
.btn-primary { background:#00285a; color:white; border:none; padding:8px 18px; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:6px; font-family:inherit; text-decoration:none; }
</style>

<div class="page-hdr">
    <div class="page-title">Static Pages</div>
    <a href="{{ route('admin.pages.create') }}" class="btn-primary">
        <i class="bi bi-plus-lg"></i> Add Page
    </a>
</div>

@if(session('success'))
<div style="background:#e8f5e9;color:#2e7d32;padding:10px 16px;border-radius:10px;margin-bottom:14px;font-size:13px;font-weight:600">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
</div>
@endif

<div class="card">
    <div style="overflow-x:auto">
        <table class="dt">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $i => $page)
                <tr>
                    <td style="color:#7a8fa6;font-size:12px">{{ $i+1 }}</td>
                    <td><strong style="color:#00285a">{{ $page->title }}</strong></td>
                    <td>
                        <a href="/page/{{ $page->slug }}" target="_blank"
                           style="font-size:12px;color:#7a8fa6;text-decoration:none">
                            /page/{{ $page->slug }} <i class="bi bi-box-arrow-up-right" style="font-size:10px"></i>
                        </a>
                    </td>
                    <td>
                        <span class="bdg {{ $page->is_active ? 'bdg-g' : 'bdg-r' }}">
                            {{ $page->is_active ? 'Active' : 'Draft' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('admin.pages.edit', $page) }}" class="btn-edit">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('admin.pages.destroy', $page) }}"
                                  onsubmit="return confirm('Delete this page?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-sm" style="background:#fce4ec;color:#c62828">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:40px;color:#7a8fa6">
                        <i class="bi bi-file-text" style="font-size:32px;display:block;margin-bottom:10px"></i>
                        No pages yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection