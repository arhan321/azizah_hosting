<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Aqlam Mural Kaligrafi'))</title>

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Nunito:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Nunito', sans-serif; }
        .navbar-brand { 
            font-family: 'Cinzel', serif; 
            font-weight: 700;
            font-size: 1.1rem;
            color: #fff !important;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
        }
        .navbar-brand img {
            filter: brightness(0) invert(1);
        }
        .navbar-dark .navbar-nav .nav-link {
            color: rgba(255,255,255,0.95) !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .navbar-dark .navbar-nav .nav-link:hover,
        .navbar-dark .navbar-nav .nav-link.active {
            color: #f5c842 !important;
            transform: translateY(-2px);
        }
        .text-primary-gold { color: #B8860B; }
        .bg-primary-gold { background-color: #B8860B; }
        .btn-gold { 
            background-color: #f5c842; 
            color: #0f7a68; 
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-gold:hover { 
            background-color: #ffd700; 
            color: #0f7a68;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        /* Fix navbar collapse overflow on small screens */
        @media (max-width: 991.98px) {
            .navbar-collapse.show, .navbar-collapse.collapsing {
                max-height: 80vh;
                overflow-y: auto;
                background: rgba(15, 122, 104, 0.95);
                padding: 1rem;
                border-radius: 8px;
                margin-top: 0.5rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-light">

{{-- Navbar --}}
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top" style="background: linear-gradient(135deg, #16a085 0%, #138f7a 50%, #0f7a68 100%); transition: all 0.3s ease;">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <img src="{{ asset('img/logo.PNG') }}" alt="Aqlam Mural" style="height:38px;width:auto;object-fit:contain;filter:brightness(0) invert(1);">
            <span class="ms-2">Aqlam Mural Kaligrafi</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('catalog.*') ? 'active' : '' }}" href="{{ route('catalog.index') }}">Katalog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('portfolio') ? 'active' : '' }}" href="{{ route('portfolio') }}">Portofolio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">Tentang Kami</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('konsultasi') }}">Konsultasi</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center">
                @auth

                {{-- Keranjang --}}
@php
    $cartCount = count(session('cart', []));
@endphp

<li class="nav-item me-2">

    <a class="nav-link position-relative"
       href="{{ route('cart.index') }}">

        <i class="bi bi-cart3 fs-5"></i>

        @if($cartCount > 0)

            <span
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark"
                style="font-size:.6rem"
            >
                {{ $cartCount }}
            </span>

        @endif

    </a>

</li>

        @if(isset($cartCount) && $cartCount > 0)
            <span
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark"
                style="font-size:.6rem"
            >
                {{ $cartCount }}
            </span>
        @endif
    </a>
</li>
                    {{-- Notifikasi --}}
                    @php
                        $unreadCount = auth()->user()->notifications()
                            ->whereNull('read_at')->count();
                    @endphp
                    <li class="nav-item me-2">
                        <a class="nav-link position-relative" href="{{ route('orders.index') }}">
                            <i class="bi bi-bell fs-5"></i>
                            @if($unreadCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:.6rem">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                            <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="bi bi-bag-check me-2 text-primary"></i>Pesanan Saya</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2 text-info"></i>Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit">
                                        <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Masuk</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-gold btn-sm ms-2" href="{{ route('register') }}">Daftar</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

{{-- Flash Messages --}}
@if(session('success') || session('error') || session('warning') || session('info'))
<div class="container mt-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>
@endif

{{-- Main Content --}}
<main class="py-4">
    @yield('content')
</main>

{{-- Footer --}}
<footer class="bg-dark text-light pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="d-flex align-items-center mb-2">
                    <img src="{{ asset('img/logo.PNG') }}" alt="Aqlam Mural" style="height:36px;width:auto;object-fit:contain;filter:brightness(0) invert(1);">
                    <p class="fw-bold mb-0 ms-2" style="font-family:'Cinzel',serif;font-size:1.1rem">Aqlam Mural Kaligrafi</p>
                </div>
                <p class="text-secondary small mb-0">Spesialis kaligrafi mural berkualitas tinggi untuk rumah, masjid, dan ruang komersial.</p>
            </div>
            <div class="col-md-4">
                <p class="fw-semibold mb-2"><i class="bi bi-geo-alt-fill me-2 text-warning"></i>Alamat</p>
                <p class="text-secondary small mb-2">
                    Jl. Rambutan 5 Jl. Komp. Bumi Asri No.06 Blok D-11,<br>
                    RT.05/RW.18, Kutabumi, Kec. Ps. Kemis,<br>
                    Kabupaten Tangerang, Banten 15560
                </p>
                <p class="text-secondary small mb-0">
                    <i class="bi bi-clock me-2 text-info"></i><strong>Jam Buka:</strong><br>
                    Senin – Jumat. 09.00 – 21.00
                </p>
            </div>
            <div class="col-md-2">
                <p class="fw-semibold mb-2">Navigasi</p>
                <ul class="list-unstyled small mb-0">
                    <li class="mb-1"><a href="{{ route('home') }}" class="text-secondary text-decoration-none"><i class="bi bi-house me-1"></i> Beranda</a></li>
                    <li class="mb-1"><a href="{{ route('catalog.index') }}" class="text-secondary text-decoration-none"><i class="bi bi-images me-1"></i> Katalog</a></li>
                    <li class="mb-1"><a href="{{ route('portfolio') }}" class="text-secondary text-decoration-none"><i class="bi bi-collection me-1"></i> Portofolio</a></li>
                    <li class="mb-1"><a href="{{ route('about') }}" class="text-secondary text-decoration-none"><i class="bi bi-info-circle me-1"></i> Tentang Kami</a></li>
                    <li class="mb-1"><a href="{{ route('custom-orders.create') }}" class="text-secondary text-decoration-none"><i class="bi bi-pencil me-1"></i> Pesanan Custom</a></li>
                </ul>
            </div>
            <div class="col-md-2">

    <p class="fw-semibold mb-2">
        Kontak
    </p>

   <p class="small mb-2">

    <a
        href="tel:089630430245"
        class="text-secondary text-decoration-none"
    >

        <i class="bi bi-telephone me-1 text-info"></i>

        0896-3043-0245

    </a>

</p>

    <p class="small mb-0">

    <a
        href="https://mail.google.com/mail/?view=cm&fs=1&to=aqlammuralll@gmail.com"
        target="_blank"
        class="text-secondary text-decoration-none"
    >

        <i class="bi bi-envelope me-1 text-primary"></i>

        aqlammuralll@gmail.com

    </a>

    </p>
    </div>
        </div>
        <hr class="border-secondary">
        <p class="text-secondary small text-center mb-0">&copy; {{ date('Y') }} Aqlam Mural Kaligrafi. All rights reserved.</p>
    </div>
</footer>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>

