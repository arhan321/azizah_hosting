@extends('layouts.admin')
@section('title', 'Detail Pesanan #' . $order->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Pesanan #{{ $order->id }}</h4>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        {{-- Detail Pelanggan --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header fw-semibold">Informasi Pelanggan</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th width="120">Nama</th><td>{{ $order->user->name }}</td></tr>
                    <tr><th>Email</th><td>{{ $order->user->email }}</td></tr>
                    <tr><th>Telepon</th><td>{{ $order->user->phone }}</td></tr>
                </table>
            </div>
        </div>

        {{-- Item Pesanan --}}
        @if($order->order_type == 'catalog')
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header fw-semibold">Item Pesanan Katalog</div>
            <div class="card-body p-0">
                @foreach($order->items as $item)
                <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                    @if($item->design->image_url)
                        <img src="{{ $item->design->image_url }}" class="rounded" style="width:70px;height:60px;object-fit:cover">
                    @endif
                    <div class="flex-grow-1">
                        <div class="fw-semibold">{{ $item->design->name }}</div>
                        <div class="small text-muted">{{ $item->design->category->name }}</div>
                        @if($item->customization_data)
                        <div class="small text-muted mt-1">
                            @foreach(array_filter($item->customization_data ?? []) as $key => $val)
                                <span class="badge bg-light text-dark border me-1">{{ str_replace('_',' ', $key) }}: {{ $val }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="fw-bold text-success">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Custom Order --}}
        @if($order->order_type == 'custom' && $order->customOrder)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header fw-semibold">Detail Pesanan Custom</div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr><th width="160">Nama Karya</th><td>{{ $order->customOrder->name }}</td></tr>
                    @if($order->customOrder->material)
                        <tr><th>Jenis Bahan</th><td>{{ ucwords(str_replace('_', ' ', $order->customOrder->material)) }}</td></tr>
                    @endif
                    @if($order->customOrder->ornament_level)
                        <tr><th>Tingkat Ornament</th><td>{{ match($order->customOrder->ornament_level) { 'simple' => 'Sederhana', 'medium' => 'Menengah', 'complex' => 'Kompleks', default => $order->customOrder->ornament_level } }}</td></tr>
                    @endif
                    @if($order->customOrder->width && $order->customOrder->height)
                        <tr><th>Ukuran Area</th><td>{{ $order->customOrder->width }} m × {{ $order->customOrder->height }} m</td></tr>
                    @elseif($order->customOrder->dimensions)
                        <tr><th>Dimensi</th><td>{{ $order->customOrder->dimensions }}</td></tr>
                    @endif
                    @if($order->customOrder->color_preference)
                        <tr><th>Warna</th><td>{{ $order->customOrder->color_preference }}</td></tr>
                    @endif
                    @if($order->customOrder->deadline)
                        <tr><th>Target</th><td>{{ \Carbon\Carbon::parse($order->customOrder->deadline)->format('d F Y') }}</td></tr>
                    @endif
                    @if($order->customOrder->address)
                        <tr><th>Alamat</th><td>{{ $order->customOrder->address }}</td></tr>
                    @endif
                    @if($order->customOrder->brief)
                        <tr><th>Brief</th><td>{{ $order->customOrder->brief }}</td></tr>
                    @endif
                </table>

                @if($order->customOrder->files->count() > 0)
                <hr>
                <p class="fw-semibold mb-2">File Referensi dari Pelanggan</p>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($order->customOrder->files as $file)
                    <a href="{{ $file->file_url }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-file-earmark me-1"></i> File {{ $loop->iteration }}
                    </a>
                    @endforeach
                </div>
                @endif

                {{-- Set Quote --}}
                <hr>
                <p class="fw-semibold mb-3">
                    <i class="bi bi-tag me-1 text-success"></i>
                    {{ $order->customOrder->admin_quote ? 'Ubah Harga Custom' : 'Tetapkan Harga Custom' }}
                    @if($order->customOrder->admin_quote)
                        <span class="ms-2 text-success">— saat ini: Rp {{ number_format($order->customOrder->admin_quote, 0, ',', '.') }}</span>
                    @endif
                </p>
                <form method="POST" action="{{ route('admin.orders.setQuote', $order->id) }}">
                    @csrf
                    <div class="row g-2">
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-semibold mb-1">Harga (Rp)</label>
                            <input type="number" name="admin_quote" class="form-control form-control-sm"
                                   placeholder="contoh: 2500000"
                                   value="{{ $order->customOrder->admin_quote }}" min="1" required>
                        </div>
                        <div class="col-12 col-md-5">
                            <label class="form-label small fw-semibold mb-1">Catatan untuk Pelanggan</label>
                            <input type="text" name="admin_notes" class="form-control form-control-sm"
                                   placeholder="contoh: Termasuk bahan & jasa pasang"
                                   value="{{ $order->customOrder->admin_notes }}">
                        </div>
                        <div class="col-12 col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-sm btn-success w-100">
                                <i class="bi bi-check-lg me-1"></i> Tetapkan & Setujui
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif

        {{-- Riwayat Pembayaran --}}
        @if($order->payments->count() > 0)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header fw-semibold">Riwayat Pembayaran</div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr><th>Tipe</th><th>Jumlah</th><th>Gateway</th><th>Status</th><th>Waktu</th></tr>
                    </thead>
                    <tbody>
                        @foreach($order->payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_type == 'dp' ? 'DP' : 'Lunas' }}</td>
                            <td class="fw-semibold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>{{ $payment->payment_method ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $payment->status == 'success' ? 'bg-success' : ($payment->status == 'pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                    {{ $payment->status }}
                                </span>
                            </td>
                            <td><small>{{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : '-' }}</small></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Upload Hasil --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header fw-semibold">Upload Dokumentasi Hasil Proyek</div>
            <div class="card-body">
                @if($order->result)
                <div class="mb-3">
                    <span class="badge bg-success me-2">File sudah ada</span>
                    <a href="{{ $order->result->download_url }}" target="_blank" class="btn btn-sm btn-outline-success me-2">Lihat</a>
                </div>
                @endif
                <form method="POST" action="{{ route('admin.orders.uploadResult', $order->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-6">
                            <input type="file" name="result_file" class="form-control form-control-sm"
                                   accept="image/jpeg,image/png,application/pdf,video/mp4" required>
                            <small class="text-muted">JPG, PNG, PDF, MP4. Maks 20MB.</small>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="result_notes" class="form-control form-control-sm"
                                   placeholder="Catatan hasil...">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-sm btn-success w-100">
                                <i class="bi bi-upload me-1"></i> Upload
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Sidebar Actions --}}
    <div class="col-lg-4">
        {{-- Update Status --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header fw-semibold">Perbarui Status</div>
            <div class="card-body">
                <div class="mb-2">
                    <span class="badge {{ match($order->status) {
                        'pending' => 'bg-warning text-dark',
                        'approved' => 'bg-info text-dark',
                        'dikerjakan' => 'bg-primary',
                        'selesai' => 'bg-success',
                        default => 'bg-secondary'
                    } }} fs-6">Status saat ini: {{ ucfirst($order->status) }}</span>
                </div>
                <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}" class="d-flex gap-2">
                    @csrf @method('PATCH')
                    <select name="status" class="form-select form-select-sm">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $order->status == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="dikerjakan" {{ $order->status == 'dikerjakan' ? 'selected' : '' }}>Dikerjakan</option>
                        <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-dark">Simpan</button>
                </form>
            </div>
        </div>

        {{-- Ringkasan --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header fw-semibold">Ringkasan</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><td>Tipe</td><td class="text-end fw-semibold">{{ ucfirst($order->order_type) }}</td></tr>
                    <tr><td>Bayar</td><td class="text-end">{{ $order->payment_type == 'dp' ? 'DP 50%' : 'Lunas' }}</td></tr>
                    <tr>
                        <td>Status Bayar</td>
                        <td class="text-end">
                            <span class="badge {{ match($order->payment_status) {
                                'unpaid' => 'bg-danger',
                                'dp_paid' => 'bg-warning text-dark',
                                'fully_paid' => 'bg-success',
                                default => 'bg-secondary'
                            } }}">{{ match($order->payment_status) {
                                'unpaid' => 'Belum Dibayar',
                                'dp_paid' => 'DP Dibayar',
                                'fully_paid' => 'Lunas',
                                default => $order->payment_status
                            } }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td class="text-end fw-bold text-success">
                            {{ $order->total_price > 0 ? 'Rp ' . number_format($order->total_price, 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
