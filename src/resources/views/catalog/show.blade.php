@extends('layouts.app')

@section('title', $design->name)

@section('content')

<div class="container py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">

        <ol class="breadcrumb">

            <li class="breadcrumb-item">
                <a href="{{ route('catalog.index') }}">
                    Katalog
                </a>
            </li>

            <li class="breadcrumb-item active">
                {{ $design->name }}
            </li>

        </ol>

    </nav>

    <div class="row g-4">

        {{-- Gambar --}}
        <div class="col-md-5">

            @if($design->image_url)

            <img
                src="{{ $design->image_url }}"
                class="img-fluid rounded shadow"
                alt="{{ $design->name }}">

            @else

            <div
                class="bg-light rounded d-flex align-items-center justify-content-center"
                style="height:350px">

                <i class="bi bi-image text-muted fs-1"></i>

            </div>

            @endif

        </div>

        {{-- Detail --}}
        <div class="col-md-7">

            <span class="badge bg-warning text-dark mb-2">
                {{ $design->category->name }}
            </span>

            <h2 class="fw-bold mb-1"
                style="font-family:'Cinzel',serif">

                {{ $design->name }}

            </h2>

            <p class="text-muted fs-5 mb-1">
                Harga Mulai Dari
            </p>

            <h2 class="text-success fw-bold mb-2">
                Rp {{ number_format($design->price, 0, ',', '.') }}
            </h2>

            <hr class="my-3">

            <h5 class="fw-bold mb-1">
                Deskripsi
            </h5>

            <div class="rich-text-content text-muted mb-4">
                {!! $design->description !!}
            </div>

            <h5 class="fw-bold mt-3 mb-1">
                Spesifikasi
            </h5>

            <div class="rich-text-content text-muted mb-4">
                {!! $design->specification !!}
            </div>

            <div class="d-flex gap-2 flex-wrap">

                @auth

                {{-- Pesan --}}
                <a
                    href="{{ route('orders.create', ['design_id' => $design->id,'category'  => $design->category->slug]) }}"
                    class="btn btn-success btn-lg px-4">

                    <i class="bi bi-bag-plus me-1"></i>

                    Pesan Desain Ini

                </a>

                {{-- Keranjang --}}


                {{-- Custom --}}
                <a
                    href="{{ route('custom-orders.create') }}"
                    class="btn btn-outline-success btn-lg px-4">

                    <i class="bi bi-pencil me-1"></i>

                    Pesan Custom

                </a>

                @else

                <a
                    href="{{ route('login') }}"
                    class="btn btn-gold btn-lg">

                    <i class="bi bi-bag-plus me-1"></i>

                    Login untuk Memesan

                </a>

                @endauth

                {{-- Konsultasi --}}
                <a
                    href="{{ route('konsultasi') }}"
                    class="btn btn-outline-success btn-lg">

                    <i class="bi bi-whatsapp me-1"></i>

                    Konsultasi

                </a>

            </div>

        </div>

    </div>

    {{-- Related --}}
    @if($related->count() > 0)

    <hr class="my-5">

    <h4 class="fw-bold mb-3">
        Desain Serupa
    </h4>

    <div class="row g-3">

        @foreach($related as $r)

        <div class="col-6 col-md-3">

            <div class="card h-100 border-0 shadow-sm">

                <a href="{{ route('catalog.show', $r->slug) }}">

                    @if($r->image_url)

                    <img
                        src="{{ $r->image_url }}"
                        class="card-img-top"
                        style="height:130px;object-fit:cover"
                        alt="{{ $r->name }}">

                    @else

                    <div
                        class="card-img-top bg-light d-flex align-items-center justify-content-center"
                        style="height:130px">

                        <i class="bi bi-image text-muted fs-2"></i>

                    </div>

                    @endif

                </a>

                <div class="card-body p-2 text-center">

                    <small class="fw-semibold text-dark d-block">

                        {{ $r->name }}

                    </small>

                    <small class="text-success fw-bold">

                        Rp {{ number_format($r->price, 0, ',', '.') }}

                    </small>

                </div>

            </div>

        </div>

        @endforeach

    </div>

    @endif

</div>

@push('styles')

<style>
    .rich-text-content {
        text-align: justify;
        line-height: 1.9;
    }

    .rich-text-content p {
        text-align: justify;
        margin-bottom: 1rem;
    }

    .rich-text-content p:last-child {
        margin-bottom: 0;
    }

    .rich-text-content ul,
    .rich-text-content ol {
        padding-left: 1.5rem;
        margin-bottom: 1rem;
    }

    .rich-text-content h4,
    .rich-text-content h5 {
        margin-top: 1.5rem;
        margin-bottom: .75rem;
        font-weight: 700;
    }
</style>

@endpush

@endsection