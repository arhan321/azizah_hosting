@extends('layouts.app')
@section('title', 'Pesanan Saya')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Pesanan Saya</h3>
            <small class="text-muted">Riwayat semua pesanan Anda. Klik Detail untuk melihat status pesanan, pembayaran, dan dokumentasi.</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('orders.create') }}" class="btn btn-gold btn-sm">
                <i class="bi bi-plus me-1"></i> Pesan Katalog
            </a>
            <a href="{{ route('custom-orders.create') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-pencil me-1"></i> Pesan Custom
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-3">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                @foreach(['pending'=>'Menunggu','approved'=>'Disetujui','dikerjakan'=>'Dikerjakan','selesai'=>'Selesai','cancelled'=>'Dibatalkan'] as $val => $label)
                <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Tipe</option>
                <option value="catalog" {{ request('type') == 'catalog' ? 'selected' : '' }}>Katalog</option>
                <option value="custom" {{ request('type') == 'custom' ? 'selected' : '' }}>Custom</option>
            </select>
        </div>
    </form>

    {{-- Orders Table --}}
    @if($orders->count() > 0)
    <div class="row g-3">
        @foreach($orders as $order)
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <strong>#{{ $order->id }}</strong>
                                <span class="badge {{ match($order->status) {
                                    'pending' => 'bg-warning text-dark',
                                    'approved' => 'bg-info text-dark',
                                    'dikerjakan' => 'bg-primary',
                                    'selesai' => 'bg-success',
                                    default => 'bg-secondary'
                                } }}">{{ ucfirst($order->status) }}</span>
                                <span class="badge bg-light text-dark border">{{ ucfirst($order->order_type) }}</span>
                            </div>
                            <small class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</small>
                            @if($order->order_type == 'catalog' && $order->items->count() > 0)
                            <div class="mt-1 small text-muted">
                                {{ $order->items->pluck('design.name')->implode(', ') }}
                            </div>
                            @elseif($order->customOrder)
                            <div class="mt-1 small text-muted">{{ $order->customOrder->name }}</div>
                            @endif
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-success">
                                @if($order->total_price > 0)
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                @else
                                <span class="text-danger fw-semibold small">Menunggu harga</span>
                                @endif
                            </div>
                            <div class="d-flex gap-1 justify-content-end mt-1">
                                <a href="{{ $order->order_type == 'custom' ? route('custom-orders.show', $order->id) : route('orders.show', $order->id) }}"
                                    class="btn btn-sm btn-outline-dark">Detail</a>
                                @if($order->status != 'cancelled' && in_array($order->payment_status, ['unpaid', 'dp_paid']) && $order->total_price > 0)
                                <a href="{{ route('payments.show', $order->id) }}"
                                    class="btn btn-sm {{ $order->payment_status == 'dp_paid' ? 'btn-primary' : 'btn-gold' }}">
                                    {{ $order->payment_status == 'dp_paid' ? 'Bayar Sisa Pelunasan' : 'Bayar Sekarang' }}
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $orders->links() }}</div>
    @else
    <div class="text-center py-5">
        <i class="bi bi-bag-x fs-1 text-muted d-block mb-3"></i>
        <p class="text-muted">Anda belum memiliki pesanan.</p>
        <a href="{{ route('catalog.index') }}" class="btn btn-gold">Mulai Pesan</a>
    </div>
    @endif
</div>
@endsection