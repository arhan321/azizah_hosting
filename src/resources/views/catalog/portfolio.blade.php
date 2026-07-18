@extends('layouts.app')
@section('title', 'Portofolio Karya')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-1 text-center" style="font-family:'Cinzel',serif">Portofolio Karya</h2>
    <p class="text-center text-muted mb-4">Seluruh koleksi kaligrafi mural kami</p>

    {{-- Grid --}}
    <div class="row g-3">
        @forelse($portfolios as $portfolio)
        <div class="col-6 col-md-4 col-lg-3">
            <div class="position-relative overflow-hidden rounded shadow-sm portfolio-item" data-bs-toggle="modal" data-bs-target="#portfolioModal{{ $portfolio->id }}">
                @if($portfolio->image_url)
                    <img src="{{ $portfolio->image_url }}" class="img-fluid w-100" alt="{{ $portfolio->title }}" style="height:200px;object-fit:cover">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height:200px">
                        <i class="bi bi-image text-muted fs-1"></i>
                    </div>
                @endif
                <div class="portfolio-overlay d-flex flex-column align-items-center justify-content-center text-white">
                    <div class="fw-semibold text-center px-2">{{ $portfolio->title }}</div>
                    @if($portfolio->is_featured)
                        <span class="badge bg-warning text-dark mt-1">Featured</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Modal Detail --}}
        <div class="modal fade" id="portfolioModal{{ $portfolio->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold">{{ $portfolio->title }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <img src="{{ $portfolio->image_url }}" class="img-fluid w-100 rounded mb-3" alt="{{ $portfolio->title }}">
                        
                        <div class="mb-3">
                            <h6 class="fw-bold text-muted mb-2">Deskripsi</h6>
                            <p class="text-secondary">{{ $portfolio->description }} </p>
                        </div>

                       <div class="row g-3">

    @if($portfolio->category)
    <div class="col-md-6">
        <h6 class="fw-bold text-muted mb-1">Kategori</h6>
        <p class="mb-0">{{ $portfolio->category->name }}</p>
    </div>
    @endif

    @if($portfolio->client_name)
    <div class="col-md-6">
        <h6 class="fw-bold text-muted mb-1">Klien</h6>
        <p class="mb-0">{{ $portfolio->client_name }}</p>
    </div>
    @endif

    @if($portfolio->location)
    <div class="col-md-6">
        <h6 class="fw-bold text-muted mb-1">Lokasi</h6>
        <p class="mb-0">{{ $portfolio->location }}</p>
    </div>
    @endif

    @if($portfolio->completion_date)
    <div class="col-md-6">
        <h6 class="fw-bold text-muted mb-1">Tanggal Selesai</h6>
        <p class="mb-0">{{ $portfolio->completion_date->format('d M Y') }}</p>
    </div>
    @endif

</div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center text-muted py-5">
            <i class="bi bi-images fs-1 d-block mb-2"></i> Belum ada karya tersedia.
        </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $portfolios->links() }}</div>
</div>

<style>
.portfolio-item { cursor: pointer; }
.portfolio-overlay {
    position: absolute; inset: 0;
    background: rgba(0,0,0,.6);
    opacity: 0; transition: opacity .3s;
}
.portfolio-item:hover .portfolio-overlay { opacity: 1; }
.modal-title{
    font-family:'Playfair Display', serif;
    font-size:25px;
}

</style>
@endsection
