@extends('layouts.app')
@section('title', 'Detail Pesanan #' . $order->id)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Pesanan Saya</a></li>
            <li class="breadcrumb-item active">Pesanan #{{ $order->id }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- Detail Pesanan --}}
        <div class="col-lg-8">
            {{-- Status --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div>
                            <h5 class="fw-bold mb-1">Pesanan #{{ $order->id }}</h5>
                            <small class="text-muted">{{ $order->created_at->format('d F Y, H:i') }}</small>
                            <div class="small text-muted mt-1">Lihat status pesanan, riwayat pembayaran, dan dokumentasi hasil di halaman ini.</div>
                        </div>
                        <div class="text-end">
                            <span class="badge fs-6 {{ match($order->status) {
                                'pending' => 'bg-warning text-dark',
                                'approved' => 'bg-info text-dark',
                                'dikerjakan' => 'bg-primary',
                                'selesai' => 'bg-success',
                                default => 'bg-secondary'
                            } }}">
                                @switch($order->status)
                                @case('pending')
                                @if($order->payment_status == 'unpaid' && $order->payments->count() == 0)
                                {{-- Tampilan saat user belum bayar sama sekali --}}
                                <i class="bi bi-wallet2 me-1"></i> Menunggu Pembayaran
                                @else
                                {{-- Tampilan saat user sudah upload bukti/bayar, tinggal tunggu dicek admin --}}
                                <i class="bi bi-clock me-1"></i> Pending
                                @endif
                                @break

                                @case('approved')
                                <i class="bi bi-check-circle me-1"></i> Disetujui
                                @break

                                @case('dikerjakan')
                                <i class="bi bi-tools me-1"></i> Sedang Dikerjakan
                                @break

                                @case('selesai')
                                <i class="bi bi-check2-all me-1"></i> Selesai
                                @break

                                @default
                                Dibatalkan
                                @endswitch
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Items --}}
            @if($order->order_type == 'catalog' && $order->items->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header fw-semibold d-flex align-items-center">
                    <i class="bi bi-list-check me-2 text-primary"></i> Detail Item Pesanan
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="2">Produk</th>
                                    <th>Detail</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                @php
                                $cdata = $item->customization_data ?? [];
                                $size = $cdata['size'] ?? null;
                                $color = $cdata['color'] ?? null;
                                $qty = $cdata['qty'] ?? 1;
                                @endphp
                                <tr>
                                    <td style="width:80px">
                                        @if($item->design->image_url)
                                        <img src="{{ $item->design->image_url }}"
                                            class="rounded"
                                            style="width:70px;height:60px;object-fit:cover">
                                        @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                            style="width:70px;height:60px">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $item->design->name }}</div>
                                        <small class="text-muted">{{ $item->design->category->name ?? '' }}</small>
                                    </td>
                                    <td class="small text-muted">
                                        @if($size)
                                        <span class="d-block">• Ukuran : {{ $size }}</span>
                                        @endif
                                        @if($color)
                                        <span class="d-block">• Warna &nbsp;: {{ $color }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $qty }}</td>
                                    <td class="text-end fw-bold text-success">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="4" class="fw-bold">
                                        Total Item ({{ $order->items->count() }} Produk)
                                    </td>
                                    <td class="text-end fw-bold text-success">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            {{-- Custom Order Detail --}}
            @if($order->order_type == 'custom' && $order->customOrder)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header fw-semibold">Detail Pesanan Custom</div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="150">Nama Karya</th>
                            <td>{{ $order->customOrder->name }}</td>
                        </tr>
                        @if($order->customOrder->material)
                        <tr>
                            <th>Jenis Bahan</th>
                            <td>{{ ucwords(str_replace('_', ' ', $order->customOrder->material)) }}</td>
                        </tr>
                        @endif
                        @if($order->customOrder->ornament_level)
                        <tr>
                            <th>Tingkat Ornament</th>
                            <td>{{ match($order->customOrder->ornament_level) { 'simple' => 'Sederhana', 'medium' => 'Menengah', 'complex' => 'Kompleks / Ornament Penuh', default => $order->customOrder->ornament_level } }}</td>
                        </tr>
                        @endif
                        @if($order->customOrder->width && $order->customOrder->height)
                        <tr>
                            <th>Ukuran Area</th>
                            <td>{{ $order->customOrder->width }} m × {{ $order->customOrder->height }} m</td>
                        </tr>
                        @elseif($order->customOrder->dimensions)
                        <tr>
                            <th>Dimensi</th>
                            <td>{{ $order->customOrder->dimensions }}</td>
                        </tr>
                        @endif
                        @if($order->customOrder->color_preference)
                        <tr>
                            <th>Preferensi Warna</th>
                            <td>{{ $order->customOrder->color_preference }}</td>
                        </tr>
                        @endif
                        @if($order->customOrder->deadline)
                        <tr>
                            <th>Target Selesai</th>
                            <td>{{ \Carbon\Carbon::parse($order->customOrder->deadline)->format('d F Y') }}</td>
                        </tr>
                        @endif
                        @if($order->customOrder->address)
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $order->customOrder->address }}</td>
                        </tr>
                        @endif
                        @if($order->customOrder->brief)
                        <tr>
                            <th>Brief</th>
                            <td>{{ $order->customOrder->brief }}</td>
                        </tr>
                        @endif
                        @if($order->customOrder->admin_notes)
                        <tr>
                            <th>Catatan Admin</th>
                            <td>{{ $order->customOrder->admin_notes }}</td>
                        </tr>
                        @endif
                    </table>

                    {{-- Files --}}
                    @if($order->customOrder->files->count() > 0)
                    <hr>
                    <p class="fw-semibold mb-2">File Referensi</p>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($order->customOrder->files as $file)
                        <a href="{{ $file->file_url }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-file-earmark me-1"></i> File {{ $loop->iteration }}
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Result --}}
            @if($order->result)
            <div class="card border-0 shadow-sm mb-4 border-success">
                <div class="card-header bg-success text-white fw-semibold">
                    <i class="bi bi-star me-2"></i> Dokumentasi Hasil Proyek
                </div>
                <div class="card-body">
                    @if($order->result->notes)
                    <p>{{ $order->result->notes }}</p>
                    @endif
                    <a href="{{ $order->result->download_url }}" target="_blank" class="btn btn-success">
                        <i class="bi bi-download me-1"></i> Unduh Dokumentasi
                    </a>
                </div>
            </div>
            @endif

            {{-- Notes --}}
            @if($order->notes)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header fw-semibold">Catatan</div>
                <div class="card-body text-muted">{{ $order->notes }}</div>
            </div>
            @endif
        </div>

        {{-- Sidebar Pembayaran --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3 position-sticky" style="top:80px">
                <div class="card-header fw-semibold d-flex align-items-center"
                    style="background:linear-gradient(135deg,#16a085,#0f7a68);color:#fff">
                    <i class="bi bi-credit-card me-2"></i> Ringkasan Pembayaran
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Total Harga</span>
                        <strong class="text-success">{{ $order->total_price > 0 ? 'Rp ' . number_format($order->total_price, 0, ',', '.') : '-' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Tipe Bayar</span>
                        <span class="small">{{ $order->payment_type == 'dp' ? 'DP 50%' : 'Lunas' }}</span>
                    </div>
                    @if($order->payment_type == 'dp' && $order->status != 'cancelled')
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">DP 50%</span>
                        <span class="small">Rp {{ number_format($order->total_price * 0.5, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Sisa Pembayaran</span>
                        <span class="small">Rp {{ number_format($order->total_price * 0.5, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted small">Status Bayar</span>

                        @if($order->status == 'cancelled')
                        <span class="badge bg-secondary">
                            Dibatalkan
                        </span>
                        @else
                        <span class="badge {{ match($order->payment_status) {
            'unpaid'      => 'bg-warning text-dark',
            'dp_paid'     => 'bg-info text-dark',
            'fully_paid'  => 'bg-success',
            'cancelled'   => 'bg-secondary',
            default       => 'bg-secondary'
        } }}">
                            {{ match($order->payment_status) {
                'unpaid' => $order->payments->contains('status', 'pending')
                    ? 'Menunggu Verifikasi'
                    : 'Menunggu Pembayaran',
                'dp_paid'    => 'DP Sudah Dibayar',
                'fully_paid' => 'Lunas',
                'cancelled'  => 'Dibatalkan',
                default      => $order->payment_status
            } }}
                        </span>
                        @endif
                    </div>

                    @if($order->payments->contains('status', 'pending'))
                    <div class="alert alert-info small py-2 mb-3">
                        <i class="bi bi-info-circle me-1"></i>
                        Silakan transfer sesuai nominal yang tertera, kemudian upload bukti transfer di halaman pembayaran.
                    </div>
                    @endif

                    @if($order->payments->count() > 0)

                    <div class="mb-3">
                        <p class="fw-semibold small mb-2">
                            <i class="bi bi-clock-history me-1"></i>
                            Riwayat Pembayaran
                        </p>

                        @foreach($order->payments as $payment)
                        <div class="border rounded p-3 mb-3 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted" style="font-size:.78rem">{{ $payment->created_at->format('d M Y, H:i') }}</div>
                                <div class="mt-1">
                                    <span class="badge {{ $payment->status == 'success' ? 'bg-success' : ($payment->status == 'pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                        {{ $payment->payment_type == 'dp' ? 'DP 50%' : 'Pelunasan' }} — {{ ucfirst($payment->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="text-end">
                                <div class="fw-semibold mb-2">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @endif

                    @if($order->payments->count() > 0)
                    <div class="d-grid mb-3">
                        <a href="{{ route('payments.detail', [$order->id, $order->payments->sortByDesc('created_at')->first()->id]) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye me-1"></i> Lihat Histori Pembayaran
                        </a>
                    </div>
                    @endif




                    @if($order->status != 'cancelled' &&in_array($order->payment_status, ['unpaid', 'dp_paid']) &&$order->total_price > 0 &&!$order->payments->contains('status', 'pending'))
                    <div class="d-grid mb-3">
                        <a href="{{ route('payments.show', $order->id) }}" class="btn btn-gold btn-sm">
                            <i class="bi bi-credit-card me-1"></i>
                            {{ $order->payment_status == 'dp_paid' ? 'Bayar Sisa Pelunasan' : 'Bayar Sekarang' }}
                        </a>
                    </div>
                    @endif

                    @if($order->status == 'pending' && $order->payments->count() == 0)

                    <div class="d-grid">

                        <button
                            type="button"
                            class="btn btn-outline-danger btn-sm w-100"
                            data-bs-toggle="modal"
                            data-bs-target="#cancelOrderModal">

                            <i class="bi bi-x-circle me-1"></i>
                            Batalkan Pesanan

                        </button>

                    </div>

                    @endif

                    @if($order->status == 'cancelled')

                    @php
                    $lastPayment = $order->payments->sortByDesc('created_at')->first();
                    @endphp

                    @if($lastPayment && $lastPayment->status == 'failed')
                    <div class="alert alert-danger small mt-3 mb-0">
                        <i class="bi bi-x-circle me-1"></i>
                        Pesanan ini telah dibatalkan oleh admin.
                    </div>
                    @else
                    <div class="alert alert-danger small mt-3 mb-0">
                        <i class="bi bi-x-circle me-1"></i>
                        Pesanan ini telah dibatalkan oleh pelanggan sebelum melakukan pembayaran.
                    </div>
                    @endif
                    @else
                    <div class="alert alert-light small mt-3 mb-0 py-2 border">
                        <i class="bi bi-info-circle me-1 text-muted"></i>
                        Pesanan Anda akan diproses setelah pembayaran dikonfirmasi oleh admin.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($order->status == 'pending' && $order->payments->count() == 0)

<div class="modal fade"
    id="cancelOrderModal"
    tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Batalkan Pesanan
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <p class="mb-2">
                    Apakah Anda yakin ingin membatalkan pesanan ini?
                </p>

                <small class="text-muted">
                    Pesanan yang telah dibatalkan tidak dapat diproses kembali.
                </small>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">

                    Batal

                </button>

                <form method="POST"
                    action="{{ route('orders.cancel', $order->id) }}">

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-danger">

                        Ya, Batalkan

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endif
@endsection