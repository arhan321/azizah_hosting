@extends('layouts.admin')
@section('title', 'Laporan Penjualan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Laporan Penjualan</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.reports.print', ['from' => $fromDate, 'to' => $toDate]) }}"
            target="_blank" class="btn btn-sm btn-outline-dark">
            <i class="bi bi-printer me-1"></i> Cetak
        </a>
        <a href="{{ route('admin.reports.export', ['from' => $fromDate, 'to' => $toDate]) }}"
            class="btn btn-sm btn-outline-success">
            <i class="bi bi-download me-1"></i> Export CSV
        </a>
    </div>
</div>

{{-- Filter Tanggal --}}
<form method="GET" class="row g-2 align-items-end mb-4">
    <div class="col-md-3">
        <label class="form-label small fw-semibold mb-1">Dari Tanggal</label>
        <input type="date" name="from" class="form-control form-control-sm" value="{{ $fromDate }}">
    </div>
    <div class="col-md-3">
        <label class="form-label small fw-semibold mb-1">Sampai Tanggal</label>
        <input type="date" name="to" class="form-control form-control-sm" value="{{ $toDate }}">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-sm btn-secondary w-100">Tampilkan</button>
    </div>
</form>

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="text-success fs-3 fw-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="text-muted small">Total Pendapatan</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="text-primary fs-3 fw-bold">{{ $totalOrders }}</div>
            <div class="text-muted small">Total Pesanan</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="row g-1">
                @foreach($ordersByStatus as $status => $count)
                <div class="col-6">
                    <small class="text-muted">{{ ucfirst($status) }}</small>
                    <div class="fw-bold">{{ $count }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Tabel Pesanan --}}
<div class="card border-0 shadow-sm">
    <div class="card-header fw-semibold">Rincian Pesanan ({{ $fromDate }} s/d {{ $toDate }})</div>
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Tipe</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Sudah Dibayar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                @php $totalPaid = $order->payments->where('status','success')->sum('amount'); @endphp
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ ucfirst($order->order_type) }}</td>
                    <td>
                        <span class="badge {{ match($order->status) {
                            'pending' => 'bg-warning text-dark',
                            'approved' => 'bg-info text-dark',
                            'dikerjakan' => 'bg-primary',
                            'selesai' => 'bg-success',
                            'cancelled' => 'bg-danger',
                            default => 'bg-secondary'
                        } }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>{{ $order->total_price > 0 ? 'Rp ' . number_format($order->total_price, 0, ',', '.') : '-' }}</td>
                    <td class="text-success fw-semibold">{{ $totalPaid > 0 ? 'Rp ' . number_format($totalPaid, 0, ',', '.') : '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Tidak ada data dalam rentang waktu ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection