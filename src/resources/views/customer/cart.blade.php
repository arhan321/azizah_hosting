@extends('layouts.app')

@section('title', 'Keranjang Saya')

@section('content')

<div class="container py-5">

    <h2 class="fw-bold mb-4">
        Keranjang Saya
    </h2>

    @php
    $subtotal = collect($cart)->sum(function ($item) {
    return $item['price'] * ($item['qty'] ?? 1);
    });

    $totalProduk = count($cart);
    @endphp

    @if(count($cart) > 0)

    <div class="row">

        {{-- DAFTAR PRODUK --}}
        <div class="col-lg-8">

            <div class="row g-4">

                @foreach($cart as $item)

                <div class="col-12 mb-3">

                    <div class="card border-0 shadow-sm rounded-4">

                        <div class="row g-0">

                            <div class="col-md-2 p-3">

                                <img
                                    src="{{ $item['image'] }}"
                                    class="rounded-3 border"
                                    style="width:100px;height:100px;object-fit:cover;">

                            </div>

                            <div class="col-md-10">

                                <div class="card-body py-3">

                                    <div class="d-flex justify-content-between">

                                        <div>

                                            <h5 class="fw-bold mb-1">
                                                {{ $item['name'] }}
                                            </h5>

                                            <h5 style="color:#16a085;" class="fw-bold mb-3">
                                                Rp {{ number_format($item['price'],0,',','.') }}
                                            </h5>

                                            <div class="small text-muted">

                                                Ukuran :
                                                <strong>{{ $item['size'] }}</strong>

                                            </div>

                                            <div class="small text-muted">

                                                Warna :
                                                <strong>{{ $item['color'] }}</strong>

                                            </div>

                                            <div class="small text-muted">

                                                Jumlah :
                                                <strong>{{ $item['qty'] }}</strong>

                                            </div>

                                            <div class="small text-muted">

                                                Alamat :
                                                <strong>{{ $item['address'] }}</strong>

                                            </div>

                                            <div class="small text-muted">

                                                Catatan :
                                                <strong>{{ $item['notes'] ?: '-' }}</strong>

                                            </div>

                                        </div>

                                        <div class="text-end d-flex flex-column justify-content-between">

                                            <form
                                                action="{{ route('cart.remove',$loop->index) }}"
                                                method="POST">

                                                @csrf

                                                <button
                                                    class="btn btn-outline-danger btn-sm">

                                                    <i class="bi bi-trash"></i>

                                                </button>

                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                @endforeach

            </div> {{-- row g-4 --}}
        </div> {{-- col-lg-8 --}}

        {{-- RINGKASAN PESANAN --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top:90px;">

                <div class="card-header text-white fw-bold" style="background:#16a085;">Ringkasan Pesanan
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">
                        <span>Total Produk</span>
                        <strong>{{ $totalProduk }}</strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Total</span>
                        <strong style="color:#16a085;">
                            Rp {{ number_format($subtotal,0,',','.') }}
                        </strong>
                    </div>
                    <hr>

                    <form action="{{ route('cart.checkout') }}" method="POST">

                        @csrf

                        <button
                            type="submit"
                            class="btn w-100 py-3 fw-bold text-white"
                            style="background:#16a085;border:none;border-radius:12px;">

                            <i class="bi bi-check-circle me-2"></i>

                            Konfirmasi Semua Pesanan

                        </button>

                    </form>
                </div>

            </div>

        </div>

    </div> {{-- row --}}

    @else

    <div class="card border-0 shadow-sm">

        <div class="card-body text-center py-5">

            <i class="bi bi-cart3 fs-1 text-secondary"></i>

            <h5 class="mt-3">
                Keranjang masih kosong
            </h5>

            <p class="text-muted mb-4">
                Silakan pilih desain dari katalog
            </p>

            <a
                href="{{ route('catalog.index') }}"
                class="btn btn-warning text-dark fw-semibold px-4">

                Lihat Katalog

            </a>

        </div>

    </div>

    @endif

</div>

@endsection