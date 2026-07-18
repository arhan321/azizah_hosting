@extends('layouts.app')
@section('title', 'Katalog Desain')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-1" style="font-family:'Cinzel',serif">Katalog Desain</h2>
    <p class="text-muted mb-4">Pilih desain kaligrafi favorit Anda</p>

    {{-- Filter --}}
    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-4">
            <select name="category" class="form-select">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                    {{ $cat->name }} ({{ $cat->designs_count }})
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Cari desain..."
                value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <select name="sort" class="form-select">
                <option value="latest" {{ request('sort','latest') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-dark w-100">Filter</button>
        </div>
    </form>

    {{-- Results --}}
    <div class="row g-3">
        @forelse($designs as $design)
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm border-0 hover-card">
                <a href="{{ route('catalog.show', $design->slug) }}">
                    @if($design->image_url)
                    <img src="{{ $design->image_url }}" class="card-img-top" alt="{{ $design->name }}" style="height:180px;object-fit:cover">
                    @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height:180px">
                        <i class="bi bi-image text-muted fs-1"></i>
                    </div>
                    @endif
                </a>
                <div class="card-body p-3">
                    <span class="badge bg-warning text-dark mb-1" style="font-size:.65rem">{{ $design->category->name }}</span>
                    <h6 class="card-title mb-1 fw-semibold">{{ $design->name }}</h6>
                    @if($design->description)
                    <div class="rich-text-preview text-muted small mb-2">{!! $design->description !!}</div>
                    @endif
                    <p class="text-success fw-bold mb-2">Rp {{ number_format($design->price, 0, ',', '.') }}</p>
                    <div class="d-flex gap-1">
                        <a href="{{ route('catalog.show', $design->slug) }}" class="btn btn-outline-dark btn-sm flex-grow-1">Detail</a>
                        @auth
                        <a href="{{ route('orders.create', ['design_id' => $design->id,'category' => $design->category->slug]) }}"
                            class="btn btn-gold btn-sm">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        @endauth

                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-search fs-1 text-muted d-block mb-2"></i>
            <p class="text-muted">Tidak ada desain yang sesuai.</p>
            <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary btn-sm">Reset Filter</a>
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $designs->links() }}
    </div>
</div>

<style>
    .hover-card {
        transition: transform .2s, box-shadow .2s;
    }

    .hover-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
    }

    .rich-text-preview {
        max-height: 70px;
        overflow: hidden;
    }

    .rich-text-preview p:last-child {
        margin-bottom: 0;
    }
</style>
@endsection