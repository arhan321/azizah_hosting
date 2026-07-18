@extends('layouts.admin')
@section('title', 'Kelola Pesanan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Kelola Pesanan</h4>
        <small class="text-muted">{{ $orders->total() }} pesanan</small>
    </div>
</div>

{{-- Filter --}}
<form method="GET" class="row g-2 mb-4">
    <div class="col-md-2">
        <select name="status" class="form-select form-select-sm">
            <option value="">Semua Status</option>
            @foreach(['pending'=>'Pending','approved'=>'Disetujui','dikerjakan'=>'Dikerjakan','selesai'=>'Selesai','cancelled'=>'Dibatalkan'] as $v => $l)
            <option value="{{ $v }}" {{ request('status') == $v ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <select name="type" class="form-select form-select-sm">
            <option value="">Semua Tipe</option>
            <option value="catalog" {{ request('type') == 'catalog' ? 'selected' : '' }}>Katalog</option>
            <option value="custom" {{ request('type') == 'custom' ? 'selected' : '' }}>Custom</option>
        </select>
    </div>
    <div class="col-md-2">
        <select name="payment_status" class="form-select form-select-sm">
            <option value="">Semua Bayar</option>
            <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
            <option value="dp_paid" {{ request('payment_status') == 'dp_paid' ? 'selected' : '' }}>DP Dibayar</option>
            <option value="fully_paid" {{ request('payment_status') == 'fully_paid' ? 'selected' : '' }}>Lunas</option>
        </select>
    </div>
    <div class="col-md-3">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari pelanggan..." value="{{ request('search') }}">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-sm btn-secondary w-100">Filter</button>
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Pelanggan</th>
                    <th>Tipe</th>
                    <th>Status</th>
                    <th>Bayar</th>
                    <th>Total</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td class="text-muted">{{ $order->id }}</td>
                    <td>
                        <div class="fw-semibold">{{ $order->user->name }}</div>
                        <small class="text-muted">{{ $order->user->phone }}</small>
                    </td>
                    <td><span class="badge bg-secondary">{{ ucfirst($order->order_type) }}</span></td>
                    <td>
                        <span class="badge {{ match($order->status) {
                            'pending' => 'bg-warning text-dark',
                            'approved' => 'bg-info text-dark',
                            'dikerjakan' => 'bg-primary',
                            'selesai' => 'bg-success',
                             'cancelled' => 'bg-danger',
                            default => 'bg-secondary'
                        } }}">{{ ucfirst($order->status) }}</span>
                    </td>
                    <td>
                        <span class="badge {{ match($order->payment_status) {
                            'unpaid' => 'bg-danger',
                            'dp_paid' => 'bg-warning text-dark',
                            'fully_paid' => 'bg-success',
                            default => 'bg-secondary'
                        } }}">{{ match($order->payment_status) {
                            'unpaid' => 'Belum',
                            'dp_paid' => 'DP',
                            'fully_paid' => 'Lunas',
                            default => $order->payment_status
                        } }}</span>
                    </td>
                    <td class="fw-semibold text-success">
                        {{ $order->total_price > 0 ? 'Rp ' . number_format($order->total_price, 0, ',', '.') : '-' }}
                    </td>
                    <td><small class="text-muted">{{ $order->created_at->format('d/m/Y') }}</small></td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-dark">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">Tidak ada pesanan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div class="card-footer">{{ $orders->links() }}</div>
    @endif
</div>
@endsection