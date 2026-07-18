@extends('layouts.app')
@section('title', 'Detail Pesanan Custom #' . $order->id)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Pesanan Saya</a></li>
            <li class="breadcrumb-item active">Pesanan Custom #{{ $order->id }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-8">
            {{-- Status --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <h5 class="fw-bold mb-1">{{ $order->customOrder->name }}</h5>
                        <small class="text-muted">Dipesan: {{ $order->created_at->format('d F Y') }}</small>
                        <div class="small text-muted mt-1">Lihat status pesanan, riwayat pembayaran, dan dokumentasi hasil di halaman ini.</div>
                    </div>
                    <div class="d-flex flex-column align-items-end gap-2">
                        <span class="badge fs-6 {{ match($order->status) {
                            'pending' => 'bg-warning text-dark',
                            'approved' => 'bg-info text-dark',
                            'dikerjakan' => 'bg-primary',
                            'selesai' => 'bg-success',
                            default => 'bg-secondary'
                        } }}">{{ ucfirst($order->status) }}</span>
                    </div>
                </div>
            </div>

            {{-- Detail --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header fw-semibold">Detail Konsep</div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        @if($order->customOrder->material)
                        <tr>
                            <th width="160">Jenis Bahan</th>
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
                            <td class="text-info">{{ $order->customOrder->admin_notes }}</td>
                        </tr>
                        @endif
                        @if($order->customOrder->admin_quote)
                        <tr>
                            <th>Harga Ditetapkan</th>
                            <td><strong class="text-success fs-5">Rp {{ number_format($order->customOrder->admin_quote, 0, ',', '.') }}</strong></td>
                        </tr>
                        @else
                        <tr>
                            <th>Harga</th>
                            <td><span class="badge bg-warning text-dark">Menunggu penetapan harga</span></td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Files --}}
            @if($order->customOrder->files->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header fw-semibold">File Referensi</div>
                <div class="card-body d-flex flex-wrap gap-2">
                    @foreach($order->customOrder->files as $file)
                    <a href="{{ $file->file_url }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-file-earmark me-1"></i> File {{ $loop->iteration }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Upload more files --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header fw-semibold">Tambah File Referensi</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('custom-orders.upload', $order->customOrder->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="d-flex gap-2 align-items-center">
                            <input type="file" name="files[]" class="form-control" multiple accept="image/jpeg,image/png,application/pdf">
                            <button type="submit" class="btn btn-dark">Upload</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Result --}}
            @if($order->result)
            <div class="card border-0 shadow-sm border-success">
                <div class="card-header bg-success text-white fw-semibold">
                    <i class="bi bi-star me-2"></i> Dokumentasi Hasil Proyek
                </div>
                <div class="card-body">
                    @if($order->result->notes) <p>{{ $order->result->notes }}</p> @endif
                    <a href="{{ $order->result->download_url }}" target="_blank" class="btn btn-success">
                        <i class="bi bi-download me-1"></i> Unduh Dokumentasi
                    </a>
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm position-sticky" style="top:80px">

                <div class="card-header fw-semibold d-flex align-items-center text-white"
                    style="background:linear-gradient(135deg,#16a085,#0f7a68)">
                    <i class="bi bi-credit-card me-2"></i>
                    Ringkasan Pembayaran
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Harga</span>
                        <strong class="text-success">
                            Rp {{ number_format($order->total_price,0,',','.') }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tipe Bayar</span>
                        <span>{{ $order->payment_type == 'dp' ? 'DP 50%' : 'Lunas' }}</span>
                    </div>

                    @if($order->payment_type == 'dp')
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">DP 50%</span>
                        <span>Rp {{ number_format($order->total_price / 2,0,',','.') }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Sisa Pembayaran</span>
                        <span>Rp {{ number_format($order->total_price / 2,0,',','.') }}</span>
                    </div>
                    @endif

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Status Bayar</span>

                        <span class="badge bg-success">
                            {{ $order->payment_status == 'fully_paid' ? 'Lunas' : 'Belum Lunas' }}
                        </span>
                    </div>

                    @if($order->payments->count())
                    <hr>

                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-clock-history me-1"></i>
                        Riwayat Pembayaran
                    </h6>

                    @foreach($order->payments as $payment)
                    <div class="border rounded p-3 mb-3">

                        <div class="d-flex justify-content-between">
                            <small class="text-muted">
                                {{ $payment->created_at->format('d M Y, H:i') }}
                            </small>

                            <strong>
                                Rp {{ number_format($payment->amount,0,',','.') }}
                            </strong>
                        </div>

                        <div class="mt-2">
                            <span class="badge bg-success">
                                {{ $payment->payment_type == 'dp' ? 'DP 50%' : 'Pelunasan' }}
                                — Success
                            </span>
                        </div>

                    </div>
                    @endforeach
                    @endif

                   @if($order->status == 'pending' && $order->total_price == 0)

<div class="d-grid mb-3">

    <button
        type="button"
        class="btn btn-outline-danger btn-sm"
        data-bs-toggle="modal"
        data-bs-target="#cancelCustomOrderModal">

        <i class="bi bi-x-circle me-1"></i>
        Batalkan Pesanan

    </button>

</div>

@endif

                    @if(in_array($order->payment_status, ['unpaid', 'dp_paid']) && $order->total_price > 0)
                    <div class="d-grid mb-3">
                        <a href="{{ route('payments.show', $order->id) }}" class="btn btn-gold btn-sm">
                            <i class="bi bi-credit-card me-1"></i>
                            {{ $order->payment_status == 'dp_paid' ? 'Bayar Sisa Pelunasan' : 'Bayar Sekarang' }}
                        </a>
                    </div>
                    @endif

                    @if($order->payments->count() > 0)
                    <div class="d-grid mb-3">
                        <a href="{{ route('payments.detail', [
                            $order->id,
                            $order->payments->sortByDesc('created_at')->first()->id
                        ]) }}"
                           class="btn btn-outline-primary w-100">
                            <i class="bi bi-eye me-1"></i>
                            Lihat Histori Pembayaran
                        </a>
                    </div>
                    @endif

                </div>

            </div>
        </div>
    </div>
</div>

@if($order->status == 'pending' && $order->total_price == 0)

<div class="modal fade" id="cancelCustomOrderModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Batalkan Pesanan</h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">
                <p class="mb-1">
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

                <form
                    action="{{ route('custom-orders.cancel', $order->id) }}"
                    method="POST">

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