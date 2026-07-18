<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — @yield('title', 'Dashboard') | Aqlam Mural Kaligrafi</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Nunito:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f1f3f5;
        }

        .sidebar {
            min-height: 100vh;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            background: #1a1a2e;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            transition: .3s;
        }

        .sidebar .brand {
            font-family: 'Cinzel', serif;
            color: #B8860B;
            font-size: 1.1rem;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, .75);
            padding: .6rem 1.2rem;
            border-radius: 6px;
            margin: 2px 8px;
            transition: .2s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(184, 134, 11, .2);
            color: #f5c842;
        }

        .sidebar .nav-link i {
            width: 20px;
        }

        .sidebar .nav-section {
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, .4);
            padding: .5rem 1.2rem;
            margin-top: .5rem;
        }

        .sidebar-brand {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .main-content {
            margin-left: 250px;
            min-height: 100vh;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: .75rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>

<body>

    {{-- Sidebar --}}
    <div class="sidebar d-flex flex-column" id="sidebar">
        <div class="sidebar-brand text-center py-4 border-bottom border-secondary">

            <a href="{{ route('home') }}" class="text-decoration-none">

                <img
                    src="{{ asset('img/logo.PNG') }}"
                    alt="Aqlam Mural Kaligrafi"
                    style="
                height:70px;
                width:auto;
                object-fit:contain;
                filter:brightness(0) invert(1);
                margin-bottom:12px;
            ">

                <h4
                    class="mb-0 fw-bold"
                    style="
        color:#f5c842;
        font-family:'Cinzel',serif;
        font-size:14px;
        letter-spacing:.5px;
        line-height:1.3;
    ">
                    Aqlam Mural Kaligrafi
                </h4>

            </a>

        </div>
        <nav class="flex-grow-1 py-2">
            <div class="nav-section">
                <i class="bi bi-grid-fill me-1"></i>Utama
            </div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>

            <div class="nav-section">
                <i class="bi bi-images me-1"></i>Katalog
            </div>
            <a href="{{ route('admin.catalog.index') }}" class="nav-link {{ request()->routeIs('admin.catalog.index') || request()->routeIs('admin.catalog.edit') || request()->routeIs('admin.catalog.create') ? 'active' : '' }}">
                <i class="bi bi-palette-fill me-2"></i> Kelola Desain
            </a>
            <a href="{{ route('admin.catalog.categories.index') }}" class="nav-link {{ request()->routeIs('admin.catalog.categories.*') ? 'active' : '' }}">
                <i class="bi bi-tags-fill me-2"></i> Kategori
            </a>
            <a href="{{ route('admin.portfolio.index') }}" class="nav-link {{ request()->routeIs('admin.portfolio.*') ? 'active' : '' }}">
                <i class="bi bi-collection-fill me-2"></i> Portofolio
            </a>

            <div class="nav-section">
                <i class="bi bi-cart-fill me-1"></i>Pesanan
            </div>
            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-bag-check-fill me-2"></i> Semua Pesanan
                @php $pending = \App\Models\Order::where('status','pending')->count(); @endphp
                @if($pending > 0)
                <span class="badge bg-danger ms-auto float-end">{{ $pending }}</span>
                @endif
            </a>
            <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <i class="bi bi-credit-card-fill me-2"></i> Pembayaran

                @php
                $pendingPayment = \App\Models\Payment::where('verification_status', 'pending')->count();
                @endphp

                @if($pendingPayment > 0)
                <span class="badge bg-danger ms-auto float-end">
                    {{ $pendingPayment }}
                </span>
                @endif
            </a>

            <div class="nav-section">
                <i class="bi bi-graph-up me-1"></i>Pelanggan & Laporan
            </div>
            <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill me-2"></i> Pelanggan
            </a>
            <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-fill me-2"></i> Laporan
            </a>
        </nav>
        <div class="p-3 border-top border-secondary">
            <small class="text-muted">Login sebagai:</small>
            <div class="text-white small fw-semibold">{{ auth()->user()->name }}</div>
            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <button class="btn btn-sm btn-outline-secondary w-100" type="submit">
                    <i class="bi bi-box-arrow-right me-1"></i> Keluar
                </button>
            </form>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="main-content">
        {{-- Topbar --}}
        <div class="topbar d-flex align-items-center justify-content-between">
            <button class="btn btn-sm btn-outline-secondary d-md-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="bi bi-list"></i>
            </button>
            <h6 class="mb-0 fw-semibold text-muted">@yield('title', 'Dashboard')</h6>
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-primary" target="_blank">
                <i class="bi bi-eye me-1"></i> Lihat Website
            </a>
        </div>

        <div class="p-4">
            {{-- Flash Messages --}}
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @yield('content')
        </div>

        {{-- Footer --}}
        <footer class="text-center text-muted py-3" style="font-size:.75rem; border-top:1px solid #dee2e6; background:#f8f9fa;">
            &copy; {{ date('Y') }} Aqlam Mural Kaligrafi. All rights reserved.
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>