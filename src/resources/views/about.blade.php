@extends('layouts.app')
@section('title', 'Tentang Kami — Aqlam Mural Kaligrafi')

@section('content')
{{-- Hero Section --}}
<div class="bg-dark text-white py-5" style="background: linear-gradient(135deg, #16a085 0%, #138f7a 100%);">
    <div class="container text-center py-4">
        <h1 class="display-5 fw-bold mb-2" style="font-family:'Cinzel',serif;">Tentang Kami</h1>
        <p class="lead mb-0">Mengenal lebih dekat Aqlam Mural Kaligrafi</p>
    </div>
</div>

{{-- About Content --}}
<div class="container py-5">
    <div class="row align-items-center mb-5">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <img src="{{ asset('img/hero-section.jpeg') }}" alt="Aqlam Mural" class="img-fluid rounded shadow">
        </div>
        <div class="col-lg-6">
            <h2 class="fw-bold mb-3" style="font-family:'Cinzel',serif; color:#16a085;">Aqlam Mural Kaligrafi</h2>
            <p class="text-muted mb-3">
                <strong>Aqlam Mural Kaligrafi</strong> adalah jasa seni yang berfokus pada pembuatan kaligrafi dan mural berkualitas tinggi. 
                Kami menghadirkan karya seni yang memadukan nilai-nilai spiritual dengan estetika modern untuk menghiasi berbagai ruang, 
                mulai dari hunian pribadi, masjid, hingga ruang komersial.
            </p>
            <p class="text-muted mb-3">
                Dengan pengalaman dan dedikasi tinggi, kami berkomitmen untuk menghadirkan karya terbaik yang tidak hanya indah dipandang, 
                tetapi juga sarat makna dan nilai. Setiap goresan kaligrafi kami dibuat dengan penuh kehati-hatian dan ketelitian 
                untuk memastikan kepuasan pelanggan.
            </p>
            <div class="d-flex gap-3 mt-4">
                <div class="text-center">
                    <div class="fs-2 fw-bold text-primary">100+</div>
                    <small class="text-muted">Proyek Selesai</small>
                </div>
                <div class="text-center">
                    <div class="fs-2 fw-bold text-success">50+</div>
                    <small class="text-muted">Klien Puas</small>
                </div>
                <div class="text-center">
                    <div class="fs-2 fw-bold text-warning">3+</div>
                    <small class="text-muted">Tahun Pengalaman</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Visi Misi --}}
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="bi bi-eye fs-3 text-primary"></i>
                        </div>
                        <h4 class="fw-bold mb-0" style="font-family:'Cinzel',serif;">Visi</h4>
                    </div>
                    <p class="text-muted mb-0">
                        Menjadi jasa seni kaligrafi dan mural terdepan di Indonesia yang menghadirkan karya seni berkualitas tinggi 
                        dengan memadukan nilai-nilai spiritual dan estetika modern untuk menginspirasi dan memperindah setiap ruang.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="bi bi-bullseye fs-3 text-success"></i>
                        </div>
                        <h4 class="fw-bold mb-0" style="font-family:'Cinzel',serif;">Misi</h4>
                    </div>
                    <ul class="text-muted mb-0 ps-3">
                        <li class="mb-2">Menghadirkan karya kaligrafi dan mural berkualitas tinggi dengan detail yang sempurna</li>
                        <li class="mb-2">Memberikan pelayanan terbaik dan konsultasi profesional kepada setiap klien</li>
                        <li class="mb-2">Terus berinovasi dalam desain dan teknik untuk menghasilkan karya yang unik dan bermakna</li>
                        <li class="mb-0">Menjaga kepercayaan pelanggan dengan hasil kerja yang memuaskan dan tepat waktu</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Keunggulan --}}
    <div class="text-center mb-4">
        <h3 class="fw-bold" style="font-family:'Cinzel',serif;">Mengapa Memilih Kami?</h3>
        <p class="text-muted">Keunggulan yang kami tawarkan untuk Anda</p>
    </div>
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100 p-4">
                <div class="bg-warning bg-opacity-10 p-3 rounded-circle mx-auto mb-3" style="width: fit-content;">
                    <i class="bi bi-award fs-1 text-warning"></i>
                </div>
                <h5 class="fw-bold mb-2">Kualitas Terjamin</h5>
                <p class="text-muted small mb-0">Setiap karya dibuat dengan standar kualitas tinggi dan detail yang sempurna</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100 p-4">
                <div class="bg-info bg-opacity-10 p-3 rounded-circle mx-auto mb-3" style="width: fit-content;">
                    <i class="bi bi-palette fs-1 text-info"></i>
                </div>
                <h5 class="fw-bold mb-2">Desain Custom</h5>
                <p class="text-muted small mb-0">Kami menerima pesanan custom sesuai keinginan dan kebutuhan Anda</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100 p-4">
                <div class="bg-success bg-opacity-10 p-3 rounded-circle mx-auto mb-3" style="width: fit-content;">
                    <i class="bi bi-people fs-1 text-success"></i>
                </div>
                <h5 class="fw-bold mb-2">Tim Profesional</h5>
                <p class="text-muted small mb-0">Dikerjakan oleh tim yang berpengalaman dan profesional di bidangnya</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100 p-4">
                <div class="bg-danger bg-opacity-10 p-3 rounded-circle mx-auto mb-3" style="width: fit-content;">
                    <i class="bi bi-clock-history fs-1 text-danger"></i>
                </div>
                <h5 class="fw-bold mb-2">Tepat Waktu</h5>
                <p class="text-muted small mb-0">Kami berkomitmen menyelesaikan setiap proyek sesuai dengan deadline yang disepakati</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100 p-4">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle mx-auto mb-3" style="width: fit-content;">
                    <i class="bi bi-chat-dots fs-1 text-primary"></i>
                </div>
                <h5 class="fw-bold mb-2">Konsultasi Gratis</h5>
                <p class="text-muted small mb-0">Dapatkan konsultasi gratis untuk mewujudkan ide kaligrafi impian Anda</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100 p-4">
                <div class="bg-secondary bg-opacity-10 p-3 rounded-circle mx-auto mb-3" style="width: fit-content;">
                    <i class="bi bi-shield-check fs-1 text-secondary"></i>
                </div>
                <h5 class="fw-bold mb-2">Garansi Kepuasan</h5>
                <p class="text-muted small mb-0">Kepuasan Anda adalah prioritas kami dengan jaminan hasil yang memuaskan</p>
            </div>
        </div>
    </div>

    {{-- Lokasi --}}
    <div class="card border-0 shadow-sm mx-auto" style="max-width: 1000px;">
        <div class="card-body p-4">
            <div class="row align-items-center">
               
                <div class="col-12">
                    <div class="ratio ratio-4x3 rounded overflow-hidden shadow-sm">
                        <iframe
                            src="https://maps.google.com/maps?q=Jl.+Komp.+Bumi+Asri+No.06+Blok+D-11,+Kutabumi,+Kec.+Ps.+Kemis,+Kabupaten+Tangerang,+Banten+15560&output=embed"
                            style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <div class="text-center mt-2">
                        <a href="https://maps.app.goo.gl/BeN6T8zb7QBx2t876" target="_blank"
                           class="btn btn-sm btn-outline-success">
                            <i class="bi bi-geo-alt me-1"></i> Buka di Google Maps
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CTA Section --}}
<div class="bg-light py-5">
    <div class="container text-center">
        <h3 class="fw-bold mb-3">Siap Mewujudkan Karya Kaligrafi Impian Anda?</h3>
        <p class="text-muted mb-4">Hubungi kami sekarang untuk konsultasi gratis dan dapatkan penawaran terbaik!</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('catalog.index') }}" class="btn btn-outline-dark btn-lg px-4">
                <i class="bi bi-images me-2"></i>Lihat Katalog
            </a>
            <a href="{{ route('konsultasi') }}" class="btn btn-gold btn-lg px-4">
                <i class="bi bi-whatsapp me-2"></i>Konsultasi Sekarang
            </a>
        </div>
    </div>
</div>
@endsection
