{{-- resources/views/admin/customers/index.blade.php --}}
@extends('admin.layouts.app')
@section('title','Customers')
@section('content')
<style>
    .customers-page-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        margin-bottom: 16px
    }

    .customers-page-title {
        font-family: 'Cinzel', serif;
        font-size: 16px;
        font-weight: 700;
        color: #00285a;
        letter-spacing: 1px
    }

    .customers-page-sub {
        margin-top: 3px;
        font-size: 12px;
        color: #7a8fa6
    }

    .customers-card {
        background: #fff;
        border: 1px solid #eef2f6;
        border-radius: 14px;
        overflow: hidden
    }

    .customers-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 18px;
        border-bottom: 1px solid #eef2f6;
        flex-wrap: wrap
    }

    .customers-card-title {
        font-family: 'Cinzel', serif;
        font-size: 12px;
        font-weight: 700;
        color: #00285a;
        letter-spacing: 1px
    }

    .customers-filters {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap
    }

    .customers-search {
        position: relative
    }

    .customers-search i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #7a8fa6;
        font-size: 13px
    }

    .customers-search input,
    .customers-select {
        height: 36px;
        border: 1.5px solid #e8edf5;
        border-radius: 8px;
        background: #fff;
        color: #334155;
        font-family: inherit;
        font-size: 13px;
        outline: none;
        transition: .15s
    }

    .customers-search input {
        width: 240px;
        padding: 0 12px 0 34px
    }

    .customers-select {
        padding: 0 11px;
        cursor: pointer
    }

    .customers-search input:focus,
    .customers-select:focus {
        border-color: #00285a;
        box-shadow: 0 0 0 3px rgba(0, 40, 90, .06)
    }

    .customers-reset {
        height: 36px;
        padding: 0 12px;
        border-radius: 8px;
        border: 1px solid #e8edf5;
        background: #f8fafc;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer
    }

    .customers-alert {
        display: none;
        margin: 14px 18px 0;
        padding: 10px 12px;
        border-radius: 10px;
        background: #fce4ec;
        color: #c62828;
        font-size: 13px
    }

    .customers-table-wrap {
        position: relative;
        min-height: 180px;
        overflow-x: auto
    }

    .customers-loading {
        display: none;
        position: absolute;
        inset: 0;
        z-index: 4;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, .78)
    }

    .customers-loading.on {
        display: flex
    }

    .customers-spinner {
        width: 30px;
        height: 30px;
        border: 3px solid #eef2f6;
        border-top-color: #00285a;
        border-radius: 50%;
        animation: customers-spin .7s linear infinite
    }

    @keyframes customers-spin {
        to {
            transform: rotate(360deg)
        }
    }

    .customers-table {
        width: 100%;
        border-collapse: collapse
    }

    .customers-table th {
        padding: 11px 14px;
        background: #f8fafc;
        border-bottom: 1px solid #eef2f6;
        color: #7a8fa6;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .8px;
        text-align: left;
        text-transform: uppercase;
        white-space: nowrap
    }

    .customers-table td {
        padding: 12px 14px;
        border-bottom: 1px solid rgba(0, 0, 0, .04);
        color: #334155;
        font-size: 13px;
        vertical-align: middle
    }

    .customers-table tr:last-child td {
        border-bottom: none
    }

    .customers-table tr:hover td {
        background: #fafbff
    }

    .customer-cell {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 230px
    }

    .customer-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, #00285a, #1e3f75);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 14px;
        font-weight: 800
    }

    .customer-name {
        color: #00285a;
        font-size: 13px;
        font-weight: 700
    }

    .customer-email {
        color: #7a8fa6;
        font-size: 11px;
        margin-top: 2px
    }

    .customer-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 9px;
        white-space: nowrap
    }

    .customer-badge-blue {
        background: #e3f2fd;
        color: #1565c0
    }

    .customer-badge-green {
        background: #e8f5e9;
        color: #2e7d32
    }

    .customer-badge-red {
        background: #fce4ec;
        color: #c62828
    }

    .customer-actions {
        display: flex;
        align-items: center;
        gap: 6px
    }

    .customer-icon-btn {
        width: 32px;
        height: 32px;
        border: 0;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        text-decoration: none;
        transition: .15s
    }

    .customer-icon-btn:hover {
        transform: translateY(-1px)
    }

    .customer-icon-view {
        background: #00285a;
        color: #fff
    }

    .customer-icon-lock {
        background: #fef9c3;
        color: #854d0e
    }

    .customer-icon-unlock {
        background: #e8f5e9;
        color: #2e7d32
    }

    .customers-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 12px 18px;
        border-top: 1px solid #eef2f6;
        flex-wrap: wrap
    }

    .customers-info {
        color: #7a8fa6;
        font-size: 12px
    }

    .customers-pages {
        display: flex;
        align-items: center;
        gap: 4px;
        flex-wrap: wrap
    }

    .customers-page-btn,
    .customers-page-gap {
        min-width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700
    }

    .customers-page-btn {
        border: 1px solid #e8edf5;
        background: #fff;
        color: #475569;
        cursor: pointer;
        text-decoration: none;
        transition: .15s
    }

    .customers-page-btn:hover {
        background: #f0f4f8
    }

    .customers-page-btn.active {
        background: #00285a;
        border-color: #00285a;
        color: #fff
    }

    .customers-page-btn.disabled {
        opacity: .42;
        pointer-events: none
    }

    .customers-page-gap {
        color: #94a3b8
    }

    @media (max-width: 720px) {
        .customers-page-head,
        .customers-card-head,
        .customers-filters {
            align-items: stretch;
            flex-direction: column
        }

        .customers-search input,
        .customers-select,
        .customers-reset {
            width: 100%
        }
    }
</style>

<div class="customers-page-head">
    <div>
        <div class="customers-page-title">Customers</div>
        <div class="customers-page-sub">Search, review, block, and unblock customer accounts.</div>
    </div>
</div>

<div class="customers-card" id="customersApp">
    <div class="customers-card-head">
        <div class="customers-card-title">All Customers <span id="customersTotal" style="color:#7a8fa6;font-weight:400;font-family:sans-serif"></span></div>
        <div class="customers-filters">
            <div class="customers-search">
                <i class="bi bi-search"></i>
                <input type="search" id="customersSearch" placeholder="Search name, email, phone..." autocomplete="off">
            </div>
            <select id="customersStatus" class="customers-select">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Blocked</option>
            </select>
            <select id="customersPerPage" class="customers-select">
                <option value="15" selected>15 / page</option>
                <option value="25">25 / page</option>
                <option value="50">50 / page</option>
            </select>
            <button type="button" id="customersReset" class="customers-reset">
                <i class="bi bi-arrow-counterclockwise"></i> Reset
            </button>
        </div>
    </div>

    <div id="customersError" class="customers-alert"></div>

    <div class="customers-table-wrap">
        <div class="customers-loading" id="customersLoading">
            <div class="customers-spinner"></div>
        </div>
        <table class="customers-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>Orders</th>
                    <th>Total Spent</th>
                    <th>Joined</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="customersBody">
                <tr>
                    <td colspan="8" style="text-align:center;padding:40px;color:#7a8fa6">Loading customers...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="customers-bottom">
        <div class="customers-info" id="customersInfo"></div>
        <div class="customers-pages" id="customersPages"></div>
    </div>
</div>

@push('scripts')
<script>
    (function() {
        var AJAX_URL = '{{ route('admin.customers.ajax') }}';
        var CSRF = document.querySelector('meta[name="csrf-token"]').content;
        var state = {
            page: 1,
            perPage: 15,
            search: '',
            status: ''
        };
        var timer = null;

        function loadCustomers() {
            setLoading(true);
            setError('');

            var params = new URLSearchParams({
                page: state.page,
                per_page: state.perPage,
                search: state.search,
                status: state.status
            });

            fetch(AJAX_URL + '?' + params.toString(), {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF
                }
            })
                .then(function(response) {
                    if (!response.ok) throw new Error('HTTP ' + response.status);
                    return response.json();
                })
                .then(function(payload) {
                    renderRows(payload.data || []);
                    renderInfo(payload.from, payload.to, payload.total);
                    renderPages(payload.current_page, payload.last_page);
                    document.getElementById('customersTotal').textContent = '(' + payload.total + ')';
                    setLoading(false);
                })
                .catch(function(error) {
                    setLoading(false);
                    setError('Unable to load customers. ' + error.message);
                    document.getElementById('customersBody').innerHTML =
                        '<tr><td colspan="8" style="text-align:center;padding:40px;color:#c62828">Failed to load customers.</td></tr>';
                });
        }

        function renderRows(rows) {
            var body = document.getElementById('customersBody');

            if (!rows.length) {
                body.innerHTML =
                    '<tr><td colspan="8" style="text-align:center;padding:44px;color:#7a8fa6"><i class="bi bi-people" style="font-size:30px;display:block;margin-bottom:8px"></i>No customers found</td></tr>';
                return;
            }

            body.innerHTML = rows.map(function(customer) {
                var name = customer.name || 'Customer';
                var initial = name.trim().charAt(0).toUpperCase() || 'C';
                var statusClass = customer.is_active ? 'customer-badge-green' : 'customer-badge-red';
                var statusText = customer.is_active ? 'Active' : 'Blocked';
                var toggleClass = customer.is_active ? 'customer-icon-lock' : 'customer-icon-unlock';
                var toggleIcon = customer.is_active ? 'lock' : 'unlock';
                var toggleTitle = customer.is_active ? 'Block customer' : 'Unblock customer';

                return '<tr data-customer-id="' + customer.id + '">' +
                    '<td><div class="customer-cell">' +
                        '<div class="customer-avatar">' + esc(initial) + '</div>' +
                        '<div><div class="customer-name">' + esc(name) + '</div>' +
                        '<div class="customer-email">' + esc(customer.email || '-') + '</div></div>' +
                    '</div></td>' +
                    '<td style="color:#64748b">' + esc(customer.phone || '-') + '</td>' +
                    '<td style="color:#64748b">' + esc(customer.city || '-') + '</td>' +
                    '<td><span class="customer-badge customer-badge-blue">' + Number(customer.orders_count || 0) + '</span></td>' +
                    '<td style="font-weight:700;color:#c44536">&#8377;' + Math.round(Number(customer.total_spent || 0)).toLocaleString('en-IN') + '</td>' +
                    '<td style="color:#64748b;font-size:12px">' + esc(customer.joined || '-') + '</td>' +
                    '<td><span class="customer-badge ' + statusClass + '">' + statusText + '</span></td>' +
                    '<td><div class="customer-actions">' +
                        '<a class="customer-icon-btn customer-icon-view" href="' + esc(customer.show_url) + '" title="View customer"><i class="bi bi-eye"></i></a>' +
                        '<button type="button" class="customer-icon-btn ' + toggleClass + '" data-toggle-url="' + esc(customer.toggle_url) + '" title="' + toggleTitle + '">' +
                            '<i class="bi bi-' + toggleIcon + '"></i>' +
                        '</button>' +
                    '</div></td>' +
                '</tr>';
            }).join('');
        }

        function renderInfo(from, to, total) {
            document.getElementById('customersInfo').textContent = total > 0 ?
                'Showing ' + from + ' - ' + to + ' of ' + total + ' customers' :
                'No customers found';
        }

        function renderPages(current, last) {
            var pages = document.getElementById('customersPages');

            if (!last || last <= 1) {
                pages.innerHTML = '';
                return;
            }

            var html = '';
            html += pageButton(current - 1, '<i class="bi bi-chevron-left"></i>', current <= 1);

            getPages(current, last).forEach(function(page) {
                if (page === 'gap') {
                    html += '<span class="customers-page-gap">...</span>';
                    return;
                }

                html += pageButton(page, page, false, page === current);
            });

            html += pageButton(current + 1, '<i class="bi bi-chevron-right"></i>', current >= last);
            pages.innerHTML = html;
        }

        function pageButton(page, label, disabled, active) {
            var classes = 'customers-page-btn';
            if (disabled) classes += ' disabled';
            if (active) classes += ' active';
            return '<a href="?page=' + page + '" class="' + classes + '" data-page="' + page + '">' + label + '</a>';
        }

        function getPages(current, last) {
            if (last <= 7) {
                return Array.from({ length: last }, function(_, index) {
                    return index + 1;
                });
            }

            var pages = [1];
            if (current > 3) pages.push('gap');

            for (var page = Math.max(2, current - 1); page <= Math.min(last - 1, current + 1); page++) {
                pages.push(page);
            }

            if (current < last - 2) pages.push('gap');
            pages.push(last);
            return pages;
        }

        function toggleCustomer(button) {
            var url = button.getAttribute('data-toggle-url');
            if (!url || button.disabled) return;

            button.disabled = true;
            button.innerHTML = '<i class="bi bi-hourglass-split"></i>';

            fetch(url, {
                method: 'PATCH',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF
                }
            })
                .then(function(response) {
                    if (!response.ok) throw new Error('HTTP ' + response.status);
                    return response.json();
                })
                .then(function() {
                    loadCustomers();
                })
                .catch(function(error) {
                    button.disabled = false;
                    setError('Unable to update customer status. ' + error.message);
                    loadCustomers();
                });
        }

        function setLoading(isLoading) {
            document.getElementById('customersLoading').classList.toggle('on', isLoading);
        }

        function setError(message) {
            var errorBox = document.getElementById('customersError');
            errorBox.textContent = message;
            errorBox.style.display = message ? 'block' : 'none';
        }

        function esc(value) {
            return String(value == null ? '' : value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        document.getElementById('customersSearch').addEventListener('input', function() {
            clearTimeout(timer);
            timer = setTimeout(function() {
                state.search = document.getElementById('customersSearch').value.trim();
                state.page = 1;
                loadCustomers();
            }, 350);
        });

        document.getElementById('customersStatus').addEventListener('change', function() {
            state.status = this.value;
            state.page = 1;
            loadCustomers();
        });

        document.getElementById('customersPerPage').addEventListener('change', function() {
            state.perPage = parseInt(this.value, 10) || 15;
            state.page = 1;
            loadCustomers();
        });

        document.getElementById('customersReset').addEventListener('click', function() {
            document.getElementById('customersSearch').value = '';
            document.getElementById('customersStatus').value = '';
            document.getElementById('customersPerPage').value = '15';
            state = { page: 1, perPage: 15, search: '', status: '' };
            loadCustomers();
        });

        document.getElementById('customersPages').addEventListener('click', function(event) {
            var link = event.target.closest('[data-page]');
            if (!link || link.classList.contains('disabled')) return;

            event.preventDefault();
            state.page = parseInt(link.getAttribute('data-page'), 10) || 1;
            loadCustomers();
        });

        document.getElementById('customersBody').addEventListener('click', function(event) {
            var button = event.target.closest('[data-toggle-url]');
            if (!button) return;
            toggleCustomer(button);
        });

        document.addEventListener('DOMContentLoaded', loadCustomers);
    })();
</script>
@endpush
@endsection
