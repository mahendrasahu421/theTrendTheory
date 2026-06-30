{{-- resources/views/admin/backups/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Database Backups')
@section('content')

<div class="admin-card">
    <div class="admin-card-header">
        <div class="admin-card-title">📁 Database Backups</div>
        <form method="POST" action="{{ route('backup.create') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn-admin btn-navy">
                <i class="bi bi-plus-lg"></i> Create New Backup
            </button>
        </form>
    </div>
    
    <div class="p-3">
        <div class="alert alert-info mb-3">
            <i class="bi bi-info-circle"></i> 
            Backups are stored in: <strong>storage/app/backups/</strong>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Size</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($backups as $backup)
                        <tr>
                            <td><code>{{ $backup['name'] }}</code></td>
                            <td>{{ $backup['size'] }}</td>
                            <td>{{ $backup['created_at']->format('d M Y, h:i A') }}</td>
                            <td>
                                <a href="{{ route('backup.download', ['filename' => $backup['name']]) }}" 
                                   class="btn btn-sm btn-success">
                                    <i class="bi bi-download"></i> Download
                                </a>
                                <form method="POST" action="{{ route('backup.delete', ['filename' => $backup['name']]) }}" 
                                      style="display:inline-block" 
                                      onsubmit="return confirm('Delete this backup?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                No backups found. Click "Create New Backup" to create your first backup.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection