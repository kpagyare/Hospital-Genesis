<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ config('app.name', 'HMS') }}</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- HMS Custom CSS -->
    <link href="{{ asset('assets/css/hms.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
<div class="wrapper">

    <!-- ══ SIDEBAR ══ -->
    <nav id="sidebar">
        <!-- Brand -->
        <div class="sidebar-brand">
            <div class="brand-logo">
                @php $settings = \App\Models\Setting::first(); @endphp
                @if($settings && $settings->logo)
                    <img src="{{ asset('storage/'.$settings->logo) }}" style="width:40px;height:40px;border-radius:50%;object-fit:cover;" alt="Logo">
                @else
                    <i class="bi bi-hospital"></i>
                @endif
            </div>
            <div class="brand-text">
                <div>{{ $settings->hospital_name ?? config('app.name') }}</div>
                <small>Management System</small>
            </div>
        </div>

        <!-- Nav -->
        <ul class="sidebar-nav">

            <!-- MAIN -->
            <li class="nav-section-title">Main</li>
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                </a>
            </li>

            <!-- PATIENTS -->
            @can_role(['super_admin','doctor','nurse','receptionist'])
            <li class="nav-section-title">Clinical</li>
            <li class="nav-item">
                <a href="{{ route('patients.index') }}" class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                    <i class="bi bi-person-lines-fill"></i> <span>Patients</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('appointments.index') }}" class="nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i> <span>Appointments</span>
                    @php $pendingCount = \App\Models\Appointment::where('status','pending')->count(); @endphp
                    @if($pendingCount > 0)
                        <span class="badge bg-danger">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>
            @endcan_role

            <!-- DOCTORS -->
            @can_role(['super_admin','receptionist'])
            <li class="nav-item">
                <a href="{{ route('doctors.index') }}" class="nav-link {{ request()->routeIs('doctors.*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge"></i> <span>Doctors</span>
                </a>
            </li>
            @endcan_role

            <!-- WARDS -->
            @can_role(['super_admin','nurse','receptionist'])
            <li class="nav-item">
                <a href="{{ route('wards.index') }}" class="nav-link {{ request()->routeIs('wards.*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i> <span>Wards & Beds</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('wards.admissions') }}" class="nav-link {{ request()->routeIs('wards.admissions*') ? 'active' : '' }}">
                    <i class="bi bi-hospital"></i> <span>Admissions</span>
                </a>
            </li>
            @endcan_role

            <!-- PHARMACY -->
            @can_role(['super_admin','pharmacist'])
            <li class="nav-section-title">Pharmacy</li>
            <li class="nav-item">
                <a href="{{ route('pharmacy.index') }}" class="nav-link {{ request()->routeIs('pharmacy.*') && !request()->routeIs('pharmacy.sales*') ? 'active' : '' }}">
                    <i class="bi bi-capsule"></i> <span>Medicines</span>
                    @php $lowStock = \App\Models\Medicine::whereRaw('stock_quantity <= low_stock_alert')->count(); @endphp
                    @if($lowStock > 0)
                        <span class="badge bg-warning text-dark">{{ $lowStock }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('pharmacy.prescriptions') }}" class="nav-link {{ request()->routeIs('pharmacy.prescriptions*') ? 'active' : '' }}">
                    <i class="bi bi-prescription"></i> <span>Prescriptions</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('pharmacy.sales') }}" class="nav-link {{ request()->routeIs('pharmacy.sales*') ? 'active' : '' }}">
                    <i class="bi bi-bag-plus"></i> <span>Medicine Sales</span>
                </a>
            </li>
            @endcan_role

            <!-- LABORATORY -->
            @can_role(['super_admin','lab_staff','doctor'])
            <li class="nav-section-title">Laboratory</li>
            <li class="nav-item">
                <a href="{{ route('laboratory.index') }}" class="nav-link {{ request()->routeIs('laboratory.*') ? 'active' : '' }}">
                    <i class="bi bi-eyedropper"></i> <span>Lab Tests</span>
                    @php $labPending = \App\Models\LabResult::where('status','pending')->count(); @endphp
                    @if($labPending > 0)
                        <span class="badge bg-warning text-dark">{{ $labPending }}</span>
                    @endif
                </a>
            </li>
            @endcan_role

            <!-- BILLING -->
            @can_role(['super_admin','accountant','receptionist'])
            <li class="nav-section-title">Finance</li>
            <li class="nav-item">
                <a href="{{ route('billing.index') }}" class="nav-link {{ request()->routeIs('billing.*') ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i> <span>Billing</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('billing.expenses') }}" class="nav-link {{ request()->routeIs('billing.expenses*') ? 'active' : '' }}">
                    <i class="bi bi-cash-stack"></i> <span>Expenses</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('reports.revenue') }}" class="nav-link {{ request()->routeIs('reports.revenue') ? 'active' : '' }}">
                    <i class="bi bi-graph-up-arrow"></i> <span>Revenue Report</span>
                </a>
            </li>
            @endcan_role

            <!-- STAFF -->
            @can_role(['super_admin'])
            <li class="nav-section-title">Administration</li>
            <li class="nav-item">
                <a href="{{ route('staff.index') }}" class="nav-link {{ request()->routeIs('staff.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> <span>Staff</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart"></i> <span>Reports</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i> <span>Settings</span>
                </a>
            </li>
            @endcan_role

        </ul>
    </nav>
    <!-- ══ END SIDEBAR ══ -->

    <!-- ══ MAIN ══ -->
    <div id="main">

        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-left">
                <button class="btn-toggle" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div class="breadcrumb-text">
                    <strong>@yield('page_title', 'Dashboard')</strong>
                    <div class="d-none d-md-block text-muted small">@yield('breadcrumb', 'Home')</div>
                </div>
            </div>
            <div class="topbar-right">
                <!-- Date -->
                <div class="d-none d-lg-flex align-items-center gap-1 text-muted small me-2">
                    <i class="bi bi-calendar3"></i>
                    {{ now()->format('D, d M Y') }}
                </div>

                <!-- Notifications -->
                <div class="dropdown">
                    <button class="notification-btn" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>
                        @php $notifCount = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count(); @endphp
                        @if($notifCount > 0)
                            <span class="badge bg-danger">{{ $notifCount }}</span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow" style="width:300px;border-radius:12px;">
                        <div class="p-3 border-bottom">
                            <strong style="font-size:14px;">Notifications</strong>
                        </div>
                        @php
                            $notifications = \App\Models\Notification::where('user_id', auth()->id())
                                ->latest()->take(5)->get();
                        @endphp
                        @forelse($notifications as $notif)
                            <a href="{{ $notif->link ?? '#' }}" class="dropdown-item p-3 border-bottom">
                                <div class="fw-600 small">{{ $notif->title }}</div>
                                <div class="text-muted" style="font-size:12px;">{{ Str::limit($notif->message, 60) }}</div>
                                <div class="text-muted" style="font-size:11px;">{{ $notif->created_at->diffForHumans() }}</div>
                            </a>
                        @empty
                            <div class="text-center p-4 text-muted small">No new notifications</div>
                        @endforelse
                    </div>
                </div>

                <!-- User Menu -->
                <div class="user-menu dropdown">
                    <button class="dropdown-toggle" data-bs-toggle="dropdown" style="border:none;background:none;cursor:pointer;">
                        <img src="{{ auth()->user()->photo_url }}" alt="Avatar" class="topbar-avatar" style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid var(--accent);">
                        <div class="d-none d-md-block text-start">
                            <div class="user-name" style="font-weight:600;font-size:13px;color:var(--primary);">{{ auth()->user()->name }}</div>
                            <div class="user-role" style="font-size:11px;color:#9ca3af;">{{ auth()->user()->role_label }}</div>
                        </div>
                        <i class="bi bi-chevron-down" style="font-size:11px;color:#9ca3af;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" style="border-radius:12px;min-width:190px;">
                        <li class="px-3 py-2">
                            <div style="font-weight:600;font-size:13px;">{{ auth()->user()->name }}</div>
                            <div style="font-size:12px;color:#9ca3af;">{{ auth()->user()->email }}</div>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li><a class="dropdown-item py-2" href="{{ route('settings.profile') }}"><i class="bi bi-person me-2"></i>My Profile</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('settings.index') }}"><i class="bi bi-gear me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-4 pt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        <!-- Page Content -->
        <main class="page-content">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="page-footer">
            &copy; {{ date('Y') }} {{ $settings->hospital_name ?? config('app.name') }} &mdash;
            Hospital Management System &bull;
            {{ $settings->footer_text ?? 'All Rights Reserved' }}
        </footer>

    </div>
    <!-- ══ END MAIN ══ -->

</div>

<!-- Mobile Overlay -->
<div id="sidebarOverlay" class="d-none d-lg-none" style="position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:999;display:none!important;"></div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Sidebar toggle
const sidebar  = document.getElementById('sidebar');
const mainEl   = document.getElementById('main');
const toggle   = document.getElementById('sidebarToggle');
const overlay  = document.getElementById('sidebarOverlay');

toggle.addEventListener('click', () => {
    if (window.innerWidth >= 992) {
        sidebar.classList.toggle('collapsed');
        mainEl.classList.toggle('expanded');
    } else {
        sidebar.classList.toggle('show');
        overlay.style.display = sidebar.classList.contains('show') ? 'block' : 'none';
    }
});

if (overlay) {
    overlay.addEventListener('click', () => {
        sidebar.classList.remove('show');
        overlay.style.display = 'none';
    });
}

// Auto-dismiss alerts after 5s
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(a => {
        const bsAlert = bootstrap.Alert.getOrCreateInstance(a);
        if (bsAlert) bsAlert.close();
    });
}, 5000);

// Initialize DataTables
document.addEventListener('DOMContentLoaded', () => {
    if ($.fn.DataTable) {
        $('.data-table').DataTable({
            pageLength: 10,
            responsive: true,
            language: { search: '', searchPlaceholder: 'Search...' },
            dom: '<"row align-items-center"<"col-sm-6"l><"col-sm-6 text-end"f>>rtip',
        });
    }
});
</script>

@stack('scripts')
</body>
</html>
