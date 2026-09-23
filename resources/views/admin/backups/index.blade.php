{{-- resources/views/admin/backups/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Database Backups & Disaster Recovery')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .backups-wrap {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        display: flex;
        flex-direction: column;
        gap: 20px;
        color: #1e293b;
        max-width: 1440px;
        margin: 0 auto;
    }

    /* 1. Studio Banner */
    .backups-banner {
        background: linear-gradient(135deg, #0b192e 0%, #0f2b54 50%, #0284c7 100%);
        border-radius: 20px;
        padding: 28px 36px;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(11, 25, 46, 0.2);
    }
    .backups-banner-text {
        position: relative;
        z-index: 2;
        max-width: 620px;
    }
    .backups-banner-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 5px 14px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: #38bdf8;
        margin-bottom: 12px;
    }
    .pulse-dot-cyan {
        width: 7px;
        height: 7px;
        background: #38bdf8;
        border-radius: 50%;
        box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.4);
    }
    .backups-banner-title {
        font-size: 26px;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin: 0 0 8px;
        color: #ffffff;
    }
    .backups-banner-desc {
        font-size: 13.5px;
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.85);
        margin: 0 0 18px;
    }

    .btn-create-backup {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff;
        border: none;
        padding: 10px 22px;
        border-radius: 11px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);
        transition: all 0.15s ease;
    }
    .btn-create-backup:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(2, 132, 199, 0.45);
    }

    .backups-banner-art {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    @media (max-width: 900px) {
        .backups-banner-art { display: none; }
    }

    /* 2. Top 3 KPI Bento Cards */
    .kpi-modern-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
    }
    @media (max-width: 800px) {
        .kpi-modern-grid { grid-template-columns: 1fr; }
    }
    .kpi-modern-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 16px;
        padding: 18px 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .kpi-card-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    .kpi-card-label {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
    }
    .kpi-icon-box {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
    }
    .kpi-icon-blue { background: #eff6ff; color: #2563eb; }
    .kpi-icon-emerald { background: #ecfdf5; color: #059669; }
    .kpi-icon-purple { background: #faf5ff; color: #9333ea; }

    .kpi-card-number {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.15;
        margin-bottom: 4px;
    }
    .kpi-card-sub {
        font-size: 11.5px;
        color: #64748b;
    }

    /* 3. Main Data Card */
    .table-modern-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 18px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        overflow: hidden;
    }

    /* Filter Toolbar */
    .table-filter-toolbar {
        padding: 14px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
    }
    .filter-search-box {
        position: relative;
        min-width: 260px;
        flex: 1;
        max-width: 380px;
    }
    .filter-search-input {
        width: 100%;
        padding: 9px 12px 9px 36px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 12.5px;
        font-family: inherit;
        outline: none;
        transition: all 0.15s ease;
        background: #ffffff;
    }
    .filter-search-input:focus {
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.08);
    }
    .filter-search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
    }
    .filter-select {
        padding: 9px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 12.5px;
        font-family: inherit;
        font-weight: 600;
        color: #334155;
        background: #ffffff;
        outline: none;
        cursor: pointer;
    }

    /* Modern Table */
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .modern-table th {
        padding: 13px 18px;
        font-size: 10.5px;
        font-weight: 800;
        color: #64748b;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        background: #fafcff;
        border-bottom: 1px solid #edf2f7;
        text-align: left;
    }
    .modern-table td {
        padding: 14px 18px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        font-size: 12.5px;
    }
    .modern-table tr:last-child td {
        border-bottom: none;
    }
    .modern-table tr:hover td {
        background: #f8fafc;
    }

    /* Action Suite Buttons */
    .action-icon-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        border: 1px solid #edf2f7;
        background: #ffffff;
        color: #475569;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .action-icon-btn.btn-download:hover {
        background: #ecfdf5;
        color: #059669;
        border-color: #a7f3d0;
    }
    .action-icon-btn.btn-delete:hover {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }

    /* ── Custom Compact Pagination (Right-aligned) ── */
    .table-footer-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 22px;
        background: #ffffff;
        border-top: 1px solid #f1f5f9;
        font-size: 12.5px;
        color: #64748b;
        width: 100%;
        box-sizing: border-box;
    }
    .table-footer-left {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12.5px;
        color: #64748b;
    }
    .table-footer-right {
        margin-left: auto;
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }
    .custom-pagination-wrap {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-left: auto;
    }
    .page-nav-btn {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .page-nav-btn:hover:not(.disabled):not(.active) {
        background: #f8fafc;
        color: #0284c7;
        border-color: #cbd5e1;
    }
    .page-nav-btn.active {
        background: #0284c7;
        color: #ffffff;
        border-color: #0284c7;
        box-shadow: 0 3px 10px rgba(2, 132, 199, 0.3);
    }
    .page-nav-btn.disabled {
        background: #f8fafc;
        color: #cbd5e1;
        border-color: #edf2f7;
        cursor: not-allowed;
    }

    /* Toast Alert */
    .backup-toast {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 99999;
        background: #0f172a;
        color: #ffffff;
        padding: 12px 20px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        display: none;
        align-items: center;
        gap: 10px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.25);
    }
</style>

<div class="backups-wrap">

    {{-- ── 1. Top Executive Studio Banner ── --}}
    <div class="backups-banner">
        <div class="backups-banner-text">
            <div class="backups-banner-badge">
                <span class="pulse-dot-cyan"></span>
                <span>DISASTER RECOVERY &amp; DATABASE SNAPSHOTS</span>
            </div>
            <h1 class="backups-banner-title">Database Backups &amp; Archive</h1>
            <p class="backups-banner-desc">
                Generate full SQL database dumps, download offline snapshots, and ensure high availability data redundancy for THE TREND THEORY.
            </p>
            <form method="POST" action="{{ route('admin.backup.create') }}" class="d-inline" id="createBackupForm">
                @csrf
                <button type="submit" class="btn-create-backup" id="btnCreateBackup">
                    <i class="bi bi-database-fill-add" id="backupBtnIcon"></i> 
                    <span id="backupBtnText">Create New Database Backup</span>
                </button>
            </form>
        </div>

        <div class="backups-banner-art">
            <svg width="200" height="120" viewBox="0 0 200 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Isometric Stacked Disks Art -->
                <ellipse cx="100" cy="35" rx="55" ry="18" fill="#38bdf8" fill-opacity="0.3" stroke="#38bdf8" stroke-width="2" />
                <path d="M45 35V60C45 70 70 78 100 78C130 78 155 70 155 60V35" fill="#ffffff" fill-opacity="0.15" stroke="#60a5fa" stroke-width="2" />
                <path d="M45 60V85C45 95 70 103 100 103C130 103 155 95 155 85V60" fill="#ffffff" fill-opacity="0.22" stroke="#60a5fa" stroke-width="2" />
                <circle cx="100" cy="35" r="8" fill="#ffffff" />
            </svg>
        </div>
    </div>

    {{-- ── 2. Top 3 KPI Bento Cards ── --}}
    <div class="kpi-modern-grid">
        {{-- Total Snapshots --}}
        <div class="kpi-modern-card">
            <div class="kpi-card-head">
                <span class="kpi-card-label">Total Backups</span>
                <div class="kpi-icon-box kpi-icon-blue">
                    <i class="bi bi-server"></i>
                </div>
            </div>
            <div class="kpi-card-number" id="kpiTotalCount">{{ $kpis['total_count'] ?? count($backups) }}</div>
            <div class="kpi-card-sub">Available in storage/app/backups</div>
        </div>

        {{-- Total Size --}}
        <div class="kpi-modern-card">
            <div class="kpi-card-head">
                <span class="kpi-card-label">Total Backup Storage</span>
                <div class="kpi-icon-box kpi-icon-emerald">
                    <i class="bi bi-hdd-fill"></i>
                </div>
            </div>
            <div class="kpi-card-number" id="kpiTotalSize" style="color:#059669;">{{ $kpis['total_size'] ?? '0 MB' }}</div>
            <div class="kpi-card-sub">Compressed SQL database volume</div>
        </div>

        {{-- Latest Snapshot --}}
        <div class="kpi-modern-card">
            <div class="kpi-card-head">
                <span class="kpi-card-label">Latest Snapshot</span>
                <div class="kpi-icon-box kpi-icon-purple">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
            <div class="kpi-card-number" id="kpiLatestSnapshot" style="font-size: 18px; color: #7c3aed;">{{ $kpis['latest_snapshot'] ?? 'No snapshots yet' }}</div>
            <div class="kpi-card-sub">Most recent recovery point</div>
        </div>
    </div>

    {{-- ── 3. Main Data Card with Filter Toolbar & AJAX DataTable ── --}}
    <div class="table-modern-card">

        {{-- Filter Toolbar --}}
        <div class="table-filter-toolbar">
            <div class="filter-search-box">
                <i class="bi bi-search filter-search-icon"></i>
                <input type="text" 
                       id="backupSearchInput" 
                       placeholder="Search backup filename..." 
                       class="filter-search-input">
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <select id="backupSortSelect" class="filter-select">
                    <option value="latest">Sort: Newest First</option>
                    <option value="oldest">Sort: Oldest First</option>
                    <option value="size_desc">Sort: Largest Size</option>
                    <option value="size_asc">Sort: Smallest Size</option>
                </select>

                <select id="backupPerPageSelect" class="filter-select">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>

                <button type="button" class="btn btn-sm btn-light" id="btnResetBackupFilters" style="border-radius: 10px; padding: 9px 13px; border: 1px solid #e2e8f0;" title="Reset filters">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </button>
            </div>
        </div>

        {{-- Table View --}}
        <div style="overflow-x: auto; position: relative;">
            {{-- Loading Overlay --}}
            <div id="tableLoadingOverlay" style="position: absolute; inset: 0; background: rgba(255,255,255,0.75); backdrop-filter: blur(2px); z-index: 10; display: none; align-items: center; justify-content: center;">
                <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <table class="modern-table">
                <thead>
                    <tr>
                        <th>BACKUP ARCHIVE FILENAME</th>
                        <th>FILE SIZE</th>
                        <th>SNAPSHOT TIMESTAMP</th>
                        <th style="text-align: right; width: 140px;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody id="backupsTableBody">
                    {{-- Dynamically populated via AJAX --}}
                </tbody>
            </table>
        </div>

        {{-- Custom Clean AJAX Pagination Footer (Left: Info, Right: Buttons) --}}
        <div class="table-footer-bar">
            <div id="backupsShowingInfo" class="table-footer-left">
                Loading database snapshots...
            </div>
            <div id="backupsPaginationContainer" class="table-footer-right">
                {{-- Dynamically populated via AJAX --}}
            </div>
        </div>
    </div>

</div>

{{-- Toast Notification --}}
<div id="backupToast" class="backup-toast">
    <i class="bi bi-check-circle-fill text-success fs-5"></i>
    <span id="backupToastMsg">Backup deleted successfully</span>
</div>

<script>
    let searchDebounceTimer = null;
    let currentPage = 1;

    document.addEventListener('DOMContentLoaded', function() {
        fetchBackups(1);

        // Handle Backup Creation Loading State
        const form = document.getElementById('createBackupForm');
        if (form) {
            form.addEventListener('submit', function() {
                const btn = document.getElementById('btnCreateBackup');
                const btnText = document.getElementById('backupBtnText');
                const btnIcon = document.getElementById('backupBtnIcon');
                if (btn) {
                    btn.disabled = true;
                    btnText.textContent = 'Generating SQL Dump...';
                    btnIcon.className = 'spinner-border spinner-border-sm';
                }
            });
        }
    });

    function showToast(msg) {
        const toast = document.getElementById('backupToast');
        const toastMsg = document.getElementById('backupToastMsg');
        if (toast && toastMsg) {
            toastMsg.textContent = msg;
            toast.style.display = 'flex';
            setTimeout(() => { toast.style.display = 'none'; }, 3000);
        }
    }

    // ── AJAX DataTable Loader ──
    function fetchBackups(page = 1) {
        currentPage = page;
        const overlay = document.getElementById('tableLoadingOverlay');
        if (overlay) overlay.style.display = 'flex';

        const search = document.getElementById('backupSearchInput').value.trim();
        const sort = document.getElementById('backupSortSelect').value;
        const perPage = document.getElementById('backupPerPageSelect').value;

        const params = new URLSearchParams();
        params.set('page', page);
        params.set('per_page', perPage);
        if (search) params.set('search', search);
        if (sort) params.set('sort', sort);

        fetch(`{{ route('admin.backups.index') }}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (overlay) overlay.style.display = 'none';
            renderBackupsTable(data.data);
            renderBackupsPagination(data);

            if (data.kpis) {
                if (document.getElementById('kpiTotalCount')) document.getElementById('kpiTotalCount').textContent = data.kpis.total_count;
                if (document.getElementById('kpiTotalSize')) document.getElementById('kpiTotalSize').textContent = data.kpis.total_size;
                if (document.getElementById('kpiLatestSnapshot')) document.getElementById('kpiLatestSnapshot').textContent = data.kpis.latest_snapshot;
            }
        })
        .catch(err => {
            if (overlay) overlay.style.display = 'none';
            console.error('Failed to load backups:', err);
        });
    }

    // ── Render Table Rows ──
    function renderBackupsTable(backups) {
        const tbody = document.getElementById('backupsTableBody');
        if (!backups || backups.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" style="text-align: center; padding: 50px 20px; color: #64748b;">
                        <i class="bi bi-database-slash" style="font-size: 36px; color: #cbd5e1; display: block; margin-bottom: 8px;"></i>
                        <strong style="color: #0f172a; font-size: 14px; display: block;">No backup snapshots found</strong>
                        <p style="font-size: 12px; margin: 4px 0 16px;">Try adjusting your search criteria or create a new backup.</p>
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        backups.forEach(b => {
            html += `
                <tr class="modern-table-row" id="backupRow_${encodeURIComponent(b.name).replace(/[^a-zA-Z0-9]/g, '_')}">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: #eff6ff; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0;">
                                <i class="bi bi-filetype-sql"></i>
                            </div>
                            <strong style="font-family: monospace; font-size: 13px; color: #0f172a;">
                                ${b.name}
                            </strong>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-navy border font-xs py-1 px-2">
                            ${b.size}
                        </span>
                    </td>
                    <td>
                        <span class="text-muted font-xs">
                            <i class="bi bi-calendar3 me-1"></i> ${b.created_at_formatted}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div class="d-flex align-items-center gap-2 justify-content-end">
                            <a href="${b.download_url}" 
                               class="action-icon-btn btn-download" 
                               title="Download SQL Backup">
                                <i class="bi bi-download"></i>
                            </a>
                            <button type="button" 
                                    onclick="deleteBackupItem('${b.name}')" 
                                    class="action-icon-btn btn-delete" 
                                    title="Delete Backup Snapshot">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    }

    // ── Render Pagination (Left: Info, Right: Buttons) ──
    function renderBackupsPagination(res) {
        document.getElementById('backupsShowingInfo').innerHTML = `
            Showing <strong>${res.from || (res.total > 0 ? 1 : 0)}</strong> to <strong>${res.to || res.total}</strong> of <strong>${res.total}</strong> database backups
        `;

        const container = document.getElementById('backupsPaginationContainer');
        if (res.last_page <= 1) {
            container.innerHTML = '';
            return;
        }

        let html = '<div class="custom-pagination-wrap">';

        // Prev
        if (res.current_page > 1) {
            html += `<button type="button" class="page-nav-btn" onclick="fetchBackups(${res.current_page - 1})"><i class="bi bi-chevron-left"></i></button>`;
        } else {
            html += `<span class="page-nav-btn disabled"><i class="bi bi-chevron-left"></i></span>`;
        }

        // Numbers
        for (let i = 1; i <= res.last_page; i++) {
            if (i === res.current_page) {
                html += `<span class="page-nav-btn active">${i}</span>`;
            } else if (i <= 2 || i >= res.last_page - 1 || Math.abs(i - res.current_page) <= 1) {
                html += `<button type="button" class="page-nav-btn" onclick="fetchBackups(${i})">${i}</button>`;
            } else if (i === 3 && res.current_page > 4) {
                html += `<span class="page-nav-btn disabled" style="border:none; background:transparent;">...</span>`;
            }
        }

        // Next
        if (res.current_page < res.last_page) {
            html += `<button type="button" class="page-nav-btn" onclick="fetchBackups(${res.current_page + 1})"><i class="bi bi-chevron-right"></i></button>`;
        } else {
            html += `<span class="page-nav-btn disabled"><i class="bi bi-chevron-right"></i></span>`;
        }

        html += '</div>';
        container.innerHTML = html;
    }

    // ── Real-time Search Listener ──
    document.getElementById('backupSearchInput').addEventListener('input', function() {
        clearTimeout(searchDebounceTimer);
        searchDebounceTimer = setTimeout(() => {
            fetchBackups(1);
        }, 350);
    });

    document.getElementById('backupSortSelect').addEventListener('change', () => fetchBackups(1));
    document.getElementById('backupPerPageSelect').addEventListener('change', () => fetchBackups(1));

    document.getElementById('btnResetBackupFilters').addEventListener('click', function() {
        document.getElementById('backupSearchInput').value = '';
        document.getElementById('backupSortSelect').value = 'latest';
        document.getElementById('backupPerPageSelect').value = '10';
        fetchBackups(1);
    });

    // ── AJAX Delete Backup ──
    function deleteBackupItem(filename) {
        if (!confirm(`Are you sure you want to permanently delete '${filename}'?`)) return;

        fetch(`/admin/backup/delete/${encodeURIComponent(filename)}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message);
                fetchBackups(currentPage);
            } else {
                alert(data.message || 'Failed to delete backup');
            }
        })
        .catch(err => alert('Failed to delete backup snapshot'));
    }
</script>
@endsection