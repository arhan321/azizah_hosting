@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

{{-- Stats Cards --}}
<div class="row g-3 mb-4">

    {{-- Total Pesanan --}}
    <div class="col-6 col-md-3">

        <a
            href="{{ route('admin.orders.index') }}"
            class="stat-link"
            style="text-decoration:none;">

            <div class="card stat-card h-100"
                style="
                border:none;
                border-radius:18px;
                overflow:hidden;
                background:#ffffff;
                box-shadow:0 6px 18px rgba(0,0,0,.05);
            ">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <h2
                                class="fw-bold mb-1"
                                style="color: #21b1b8;">
                                {{ $totalOrders }}
                            </h2>

                            <div class="text-muted">
                                Total Pesanan
                            </div>

                        </div>

                        <i
                            class="bi bi-bag-check fs-2"
                            style="color:#14b8a6;"></i>

                    </div>

                </div>

                <div
                    class="px-4 py-2"
                    style="
                    background:#ccfbf1;
                    color:#0f766e;
                    font-size:13px;
                    font-weight:500;
                ">

                    Lihat Semua Pesanan →

                </div>

            </div>

        </a>

    </div>
    {{-- Pending --}}
    <div class="col-6 col-md-3">

        <a
            href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
            class="stat-link"
            style="text-decoration:none;
        ">

            <div class="card stat-card h-100"
                style="
                border:none;
                border-radius:18px;
                overflow:hidden;
                background:#ffffff;
                box-shadow:0 6px 18px rgba(0,0,0,.05);
            ">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <h2
                                class="fw-bold mb-1"
                                style="color:#f59e0b;">
                                {{ $pendingOrders }}
                            </h2>

                            <div class="text-muted">
                                Pending
                            </div>

                        </div>

                        <i
                            class="bi bi-clock-history fs-2"
                            style="color:#f59e0b;"></i>

                    </div>

                </div>

                <div
                    class="px-4 py-2"
                    style="
                    background:#fff4df;
                    color:#f59e0b;
                    font-size:13px;
                    font-weight:500;
                ">

                    Lihat Pesanan →

                </div>

            </div>

        </a>

    </div>

    {{-- Sedang Dikerjakan --}}
    <div class="col-6 col-md-3">

        <a
            href="{{ route('admin.orders.index', ['status' => 'dikerjakan']) }}"
            class="stat-link"
            style="text-decoration:none;">

            <div class="card stat-card h-100"
                style="
                border:none;
                border-radius:18px;
                overflow:hidden;
                background:#ffffff;
                box-shadow:0 6px 18px rgba(0,0,0,.05);
            ">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <h2
                                class="fw-bold mb-1"
                                style="color:#2563eb;">
                                {{ $ongoingOrders }}
                            </h2>

                            <div class="text-muted">
                                Sedang Dikerjakan
                            </div>

                        </div>

                        <i
                            class="bi bi-hourglass-split fs-2"
                            style="color:#2563eb;"></i>

                    </div>

                </div>

                <div
                    class="px-4 py-2"
                    style="
                    background:#e8f1ff;
                    color:#2563eb;
                    font-size:13px;
                    font-weight:500;
                ">

                    Lihat Pesanan →

                </div>

            </div>

        </a>

    </div>

    {{-- Total Pelanggan --}}
    <div class="col-6 col-md-3">

        <a
            href="{{ route('admin.customers.index') }}"
            class="stat-link"
            style="text-decoration:none;">

            <div class="card stat-card h-100"
                style=" border:none;
                border-radius:18px;
                overflow:hidden;
                background:#ffffff;
                box-shadow:0 6px 18px rgba(0,0,0,.05);
            ">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <h2
                                class="fw-bold mb-1"
                                style="color:#16a34a;">
                                {{ $totalCustomers }}
                            </h2>

                            <div class="text-muted">
                                Total Pelanggan
                            </div>

                        </div>

                        <i
                            class="bi bi-people-fill fs-2"
                            style="color:#16a34a;"></i>

                    </div>

                </div>

                <div
                    class="px-4 py-2"
                    style="
                    background:#dcfce7;
                    color:#16a34a;
                    font-size:13px;
                    font-weight:500;
                ">

                    Lihat Pelanggan →

                </div>

            </div>

        </a>

    </div>

</div>

<div class="row g-4">
    {{-- Pesanan Terbaru --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-list-ul text-primary fs-5 me-2"></i>
                    <span class="fw-semibold">Pesanan Terbaru</span>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-arrow-right me-1"></i>Lihat Semua
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th><i class="bi bi-person me-1"></i>Pelanggan</th>
                            <th><i class="bi bi-tag me-1"></i>Tipe</th>
                            <th><i class="bi bi-info-circle me-1"></i>Status</th>
                            <th><i class="bi bi-cash me-1"></i>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td class="text-muted">#{{ $order->id }}</td>
                            <td>
                                <div class="fw-semibold">{{ $order->user->name }}</div>
                                <small class="text-muted">{{ $order->user->email }}</small>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ ucfirst($order->order_type) }}</span></td>
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
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-dark">
                                    <i class="bi bi-eye me-1"></i>Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                Belum ada pesanan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-4">

        {{-- Revenue --}}
        <div class="card border-0 shadow-sm mb-3"
            style="
            background:#ffffff;
            border-radius:20px;
        ">

            <div class="card-body text-center p-4">

                <div
                    class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                    style="
                    width:75px;
                    height:75px;
                    background:#fff7d6;
                    color:#d97706;
                ">

                    <i class="bi bi-cash-coin fs-1"></i>

                </div>

                <div class="text-muted small mb-2">
                    Total Pendapatan
                </div>

                <div class="fw-bold fs-3 text-dark">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </div>

            </div>

        </div>

        {{-- Top Designs --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-star-fill text-warning fs-5 me-2"></i>
                    <span class="fw-semibold">Desain Terpopuler</span>
                </div>
            </div>
            <ul class="list-group list-group-flush">
                @forelse($topDesigns as $i => $design)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <span class="badge {{ $i === 0 ? 'bg-warning' : ($i === 1 ? 'bg-secondary' : 'bg-dark') }} me-2">
                            {{ $i === 0 ? '🥇' : ($i === 1 ? '🥈' : ($i === 2 ? '🥉' : $i+1)) }}
                        </span>
                        <span>{{ $design->name }}</span>
                    </div>
                    <span class="badge bg-primary rounded-pill">
                        <i class="bi bi-cart-check me-1"></i>{{ $design->order_items_count }}
                    </span>
                </li>
                @empty
                <li class="list-group-item text-muted text-center py-4">
                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                    Belum ada data
                </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection