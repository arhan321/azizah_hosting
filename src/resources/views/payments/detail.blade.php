@extends('layouts.app')
@section('title', 'Detail Pembayaran #' . $order->id)

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-0">Detail Pembayaran</h4>
            <small class="text-muted">Pesanan #{{ $order->id }} — {{ $payment->created_at->format('d M Y, H:i') }}</small>
        </div>
        <div>
            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Pesanan
            </a>
        </div>
    </div>

    @php
    $payments = $order->payments->sortBy(fn($item) => $item->payment_type === 'dp' ? 0 : 1);
    $totalPaid = $payments->where('status', 'success')->sum('amount');
    $remaining = max(0, $order->total_price - $totalPaid);
    @endphp

    <div class="row g-4">
        <div class="col-lg-8">
            @foreach($payments as $history)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge {{ $history->status == 'success' ? 'bg-success' : ($history->status == 'pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                            {{ $history->payment_type === 'dp' ? 'DP 50%' : 'Pelunasan' }} — {{ ucfirst($history->status) }}
                        </span>
                    </div>
                    <div class="text-end small text-muted">
                        {{ $history->created_at->format('d M Y, H:i') }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-md-6">
                            <div class="text-muted small">Nominal Transfer</div>
                            <div class="fw-bold fs-5 text-success">Rp {{ number_format($history->amount, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Tanggal Transfer</div>
                            <div class="fw-bold">{{ optional($history->transfer_date)->format('d M Y, H:i') ?? '-' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small">Nama Pengirim</div>
                            <div class="fw-semibold">{{ $history->account_holder ?? '-' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small">Bank Pengirim</div>
                            <div class="fw-semibold">{{ $history->bank_name ?? '-' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small">Nomor Rekening</div>
                            <div class="fw-semibold">{{ $history->account_number ?? '-' }}</div>
                        </div>

                        @if($history->verification_notes)
                        <div class="col-12">
                            <div class="text-muted small">Catatan Verifikasi</div>
                            <div class="fw-semibold text-danger">{{ $history->verification_notes }}</div>
                        </div>
                        @endif

                        <div class="col-12">
                            <div class="text-muted small">Status Verifikasi</div>
                            <div class="fw-semibold">{{ ucfirst($history->verification_status ?? '-') }}</div>
                        </div>

                        <div class="col-12">
                            <div class="text-muted small">Bukti Transfer</div>
                            @if($history->payment_proof_url)
                            <a href="{{ $history->payment_proof_url }}" target="_blank">
                                <img src="{{ $history->payment_proof_url }}" class="img-fluid rounded border" style="max-height:220px; width:100%; object-fit:contain">
                            </a>
                            <div class="mt-2 small text-muted">Klik gambar untuk melihat ukuran penuh.</div>
                            @else
                            <div class="text-muted">Tidak ada bukti transfer tersedia.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header fw-semibold" style="background:#fff8e1">
                    Ringkasan Pembayaran
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="text-muted small">Total Tagihan</div>
                        <div class="fw-bold fs-5">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small">Total Dibayar</div>
                        <div class="fw-bold fs-5">Rp {{ number_format($totalPaid, 0, ',', '.') }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small">Sisa Pembayaran</div>
                        <div class="fw-bold fs-5 text-danger">Rp {{ number_format($remaining, 0, ',', '.') }}</div>
                    </div>
                    <div class="alert alert-light small py-2 mb-0">
                        <i class="bi bi-info-circle me-1 text-muted"></i>
                        Semua bukti pembayaran DP dan Pelunasan ditampilkan di halaman ini.
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body small text-muted">
                    <div>Halaman ini menampilkan seluruh detail pembayaran untuk Pesanan #{{ $order->id }}.</div>
                    <div class="mt-2">Jika ada pembayaran yang belum terverifikasi, tunggu admin memproses bukti transfer.</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection