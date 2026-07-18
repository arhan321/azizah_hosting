@extends('layouts.app')
@section('title', 'Beranda — Aqlam Mural Kaligrafi')

@section('content')
{{-- Hero Section --}}
<div class="hero-section text-white py-5 position-relative" style="background: linear-gradient(135deg, rgba(15, 122, 104, 0.85) 0%, rgba(19, 143, 122, 0.75) 50%, rgba(22, 160, 133, 0.65) 100%), url('/img/hero-section.jpeg') center/cover no-repeat; min-height: 500px;">
    <div class="container py-5 text-center position-relative" style="z-index: 2;">
        <h1 class="display-4 fw-bold mb-3" style="font-family:'Cinzel',serif; color:#f5c842; text-shadow: 3px 3px 6px rgba(0,0,0,0.8);">
            AQLAM MURAL KALIGRAFI
        </h1>
        <p class="lead mb-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8); font-size: 1.3rem;">Seni kaligrafi dan mural yang menghidupkan setiap ruang<br>dengan nilai nilai dan estetika</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('catalog.index') }}" class="btn btn-light btn-lg px-5 shadow-lg" style="font-weight: 600;">
                <i class="bi bi-images me-2"></i> Lihat Katalog
            </a>
            <a href="{{ route('orders.create') }}" class="btn btn-gold btn-lg px-5 shadow-lg">
                <i class="bi bi-pencil-square me-2"></i> Pesan Sekarang
            </a>
        </div>
    </div>
</div>

{{-- Featured Designs --}}
<div class="container py-5">
    <h2 class="text-center fw-bold mb-1" style="font-family:'Cinzel',serif">Karya Unggulan</h2>
    <p class="text-center text-muted mb-4">Koleksi terbaik dari Aqlam Mural Kaligrafi</p>

    <div class="row g-3">
        @forelse($featuredDesigns as $design)
        <div class="col-6 col-md-4 col-lg-2">
            <a href="{{ route('catalog.show', $design->slug) }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 hover-card">
                    @if($design->image_url)
                        <img src="{{ $design->image_url }}" class="card-img-top" alt="{{ $design->name }}" style="height:140px;object-fit:cover">
                    @else
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height:140px">
                            <i class="bi bi-image text-white fs-2"></i>
                        </div>
                    @endif
                    <div class="card-body p-2 text-center">
                        <p class="mb-0 small fw-semibold text-dark">{{ $design->name }}</p>
                        <small class="text-success fw-bold">Rp {{ number_format($design->price, 0, ',', '.') }}</small>
                    </div>
                </div>
            </a>
        </div>
        @empty
        <div class="col-12 text-center text-muted py-5">
            <i class="bi bi-images fs-1 d-block mb-2"></i>
            Belum ada desain tersedia.
        </div>
        @endforelse
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('catalog.index') }}" class="btn btn-outline-dark">
            Lihat Semua Katalog <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
</div>

{{-- Categories --}}
@if($categories->count() > 0)
<div class="bg-white py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-4" style="font-family:'Cinzel',serif">Kategori</h2>
        <div class="row g-3 justify-content-center">
            @foreach($categories as $cat)
            <div class="col-6 col-md-3 col-lg-2">
                <a href="{{ route('catalog.index', ['category' => $cat->slug]) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm text-center p-3 h-100">
                        <i class="bi bi-bookmark-star fs-1 text-warning d-block mb-2"></i>
                        <div class="fw-semibold text-dark">{{ $cat->name }}</div>
                        <small class="text-muted">{{ $cat->designs_count }} desain</small>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- CTA Section --}}
<div class="container py-5 text-center">
    <h3 class="fw-bold mb-2">Ingin Desain Custom?</h3>
    <p class="text-muted mb-4">Konsultasikan ide kaligrafi Anda dengan kami. Kami siap mewujudkan karya impian Anda.</p>
    @auth
        <a href="{{ route('custom-orders.create') }}" class="btn btn-gold btn-lg px-5">
            <i class="bi bi-pencil me-1"></i> Pesan Custom Sekarang
        </a>
    @else
        <a href="{{ route('register') }}" class="btn btn-gold btn-lg px-5 me-2">Daftar & Pesan Sekarang</a>
        <a href="{{ route('konsultasi') }}" class="btn btn-outline-secondary btn-lg">Konsultasi Dulu</a>
    @endauth
</div>

<style>
.hover-card { transition: transform .2s, box-shadow .2s; }
.hover-card:hover { transform: translateY(-4px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important; }
</style>
@endsection
