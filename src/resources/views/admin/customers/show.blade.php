@extends('layouts.admin')
@section('title', 'Detail Pelanggan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Detail Pelanggan</h4>
    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-4">
                <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                     style="width:70px;height:70px;font-size:1.8rem">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
                <div class="text-muted small">{{ $user->email }}</div>
                <div class="text-muted small">{{ $user->phone }}</div>
                <div class="mt-2 text-muted small">Bergabung {{ $user->created_at->format('d F Y') }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header fw-semibold">Riwayat Pesanan</div>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>#</th><th>Tipe</th><th>Status</th><th>Total</th><th>Tanggal</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst($order->order_type) }}</span></td>
                            <td>
                                <span class="badge {{ match($order->status) {
                                    'pending' => 'bg-warning text-dark',
                                    'approved' => 'bg-info text-dark',
                                    'dikerjakan' => 'bg-primary',
                                    'selesai' => 'bg-success',
                                    default => 'bg-secondary'
                                } }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td>{{ $order->total_price > 0 ? 'Rp ' . number_format($order->total_price, 0, ',', '.') : '-' }}</td>
                            <td><small>{{ $order->created_at->format('d/m/Y') }}</small></td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-dark">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted">Belum ada pesanan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($orders->hasPages())
            <div class="card-footer">{{ $orders->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
