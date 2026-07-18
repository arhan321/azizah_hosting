@extends('layouts.app')
@section('title', 'Pembayaran Pesanan #' . $order->id)

@php
use App\Constants\BankAccount;
$bankInfo = BankAccount::getInfo();
$totalPaid = $order->payments->where('status','success')->sum('amount');
$remaining = $order->total_price - $totalPaid;
@endphp

@section('content')
<div class="container py-4">

    <h3 class="fw-bold mb-1">Pembayaran Pesanan #{{ $order->id }}</h3>
    <p class="text-muted small mb-4">
        Tanggal Pemesanan: {{ $order->created_at->format('d M Y, H:i') }}
    </p>

    <div class="row g-4">
        {{-- KIRI: Ringkasan + Form Upload --}}
        <div class="col-lg-8">

            {{-- Ringkasan Pesanan --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header fw-semibold">Ringkasan Pesanan</div>
                <div class="card-body p-0">
                    @if($order->order_type == 'catalog')
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="2">Produk</th>
                                    <th>Ukuran</th>
                                    <th>Warna</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                @php
                                $cdata = $item->customization_data ?? [];
                                $size = $cdata['size'] ?? '-';
                                $color = $cdata['color'] ?? '-';
                                $qty = $cdata['qty'] ?? 1;
                                @endphp
                                <tr>
                                    <td style="width:56px">
                                        @if($item->design->image_url)
                                        <img src="{{ $item->design->image_url }}"
                                            style="width:48px;height:40px;object-fit:cover;border-radius:6px">
                                        @else
                                        <div class="bg-light d-flex align-items-center justify-content-center"
                                            style="width:48px;height:40px;border-radius:6px">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold small">{{ $item->design->name }}</div>
                                    </td>
                                    <td class="small text-muted">{{ $size }}</td>
                                    <td class="small text-muted">{{ $color }}</td>
                                    <td class="text-center small">{{ $qty }}</td>
                                    <td class="text-end fw-bold text-success small">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="p-3">
                        <div class="d-flex justify-content-between">
                            <span>{{ $order->customOrder->name ?? 'Pesanan Custom' }}</span>
                            <span class="fw-bold text-success">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @endif

                    {{-- Totals --}}
                    <div class="p-3 border-top bg-light">
                        <div class="d-flex justify-content-between fw-bold mb-1">
                            <span>Total Harga ({{ $order->items->count() }} Produk)</span>
                            <span class="text-success">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small mb-1">
                            <span>Tipe Bayar</span>
                            <span>{{ $order->payment_type == 'dp' ? 'DP 50%' : 'Lunas' }}</span>
                        </div>
                        @if($order->payment_type == 'dp')
                        <div class="d-flex justify-content-between text-muted small mb-1">
                            <span>DP 50%</span>
                            <span>Rp {{ number_format($order->total_price * 0.5, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small mb-1">
                            <span>Sisa Pembayaran</span>
                            <span>Rp {{ number_format($order->total_price * 0.5, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        @if($totalPaid > 0)
                        <div class="d-flex justify-content-between text-muted small mb-1">
                            <span>Sudah Dibayar</span>
                            <span class="text-success">- Rp {{ number_format($totalPaid, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between small mt-1">
                            <span>Status Bayar</span>

                            @if($order->payments->contains('status', 'pending'))
                            <span class="badge bg-info text-dark">
                                Menunggu Verifikasi
                            </span>

                            @elseif($order->payment_status == 'fully_paid')
                            <span class="badge bg-success">
                                Lunas
                            </span>

                            @elseif($order->payment_status == 'dp_paid')
                            <span class="badge bg-primary">
                                DP Dibayar
                            </span>

                            @else
                            <span class="badge bg-warning text-dark">
                                Menunggu Pembayaran
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($order->payment_status == 'fully_paid')
            <div class="card border-0 shadow-sm border-success">
                <div class="card-body text-center py-4">
                    <i class="bi bi-check-circle-fill text-success fs-1 mb-3"></i>
                    <h5 class="fw-bold mb-1 text-success">Pembayaran Lunas</h5>
                    <p class="text-muted small mb-0">Pesanan Anda telah dibayar lunas dan sedang diproses.</p>
                </div>
            </div>
            @elseif($order->payments->contains('status', 'pending'))
            <div class="card border-0 shadow-sm border-warning">
                <div class="card-body text-center py-4">
                    <i class="bi bi-clock-history text-warning fs-1 mb-3"></i>
                    <h5 class="fw-bold mb-1 text-warning">Menunggu Verifikasi</h5>
                    <p class="text-muted small mb-0">Bukti pembayaran Anda sedang diverifikasi oleh admin. Mohon tunggu beberapa saat.</p>
                </div>
            </div>
            @else
            {{-- Upload Bukti Transfer --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header fw-semibold">Upload Bukti Transfer</div>
                <div class="card-body">
                    <form id="paymentForm" method="POST"
                        action="{{ route('payments.process', $order->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="payment_type" id="payment_type"
                            value="{{ ($order->payment_type == 'dp' && $order->payment_status == 'unpaid') ? 'dp' : 'full' }}">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Nama Bank Pengirim <span class="text-danger">*</span>
                                </label>
                                <select name="bank_name" class="form-select" required>
                                    <option value="">Pilih bank pengirim</option>
                                    @foreach(['BCA','Mandiri','BRI','BNI','BSI','CIMB Niaga','Danamon','Permata'] as $b)
                                    <option value="{{ $b }}">{{ $b }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Contoh: BCA, Mandiri, BRI</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Nominal Transfer <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number"
                                        class="form-control"
                                        value="{{ $order->payment_status == 'dp_paid' ? round($remaining) : ($order->payment_type == 'dp' ? round($order->total_price * 0.5) : round($order->total_price)) }}"
                                        readonly>

                                </div>
                                <small class="text-muted">Pastikan nominal sesuai dengan yang tertera.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Nama Pemegang Rekening <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    name="account_holder"
                                    class="form-control"
                                    placeholder="Nama sesuai rekening"
                                    value="{{ old('account_holder') }}"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nomor Rekening Pengirim *</label>
                                <input type="text"
                                    name="account_number"
                                    class="form-control"
                                    placeholder="Masukkan nomor rekening"
                                    value="{{ old('account_number') }}"
                                    required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Catatan (Opsional)</label>
                                <input type="text" name="notes" class="form-control"
                                    placeholder="Tulis catatan jika ada">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    Upload Bukti Transfer <span class="text-danger">*</span>
                                </label>
                                <div class="border rounded p-4 text-center" id="dropZone"
                                    style="cursor:pointer;border-style:dashed!important">
                                    <i class="bi bi-cloud-upload fs-2 text-muted d-block mb-2"></i>
                                    <p class="text-muted small mb-1">Klik atau seret file ke sini untuk mengupload</p>
                                    <p class="text-muted" style="font-size:.75rem">Format: JPG, PNG, JPEG (Maks. 5MB)</p>
                                    <input type="file" name="payment_proof" id="proofFile"
                                        class="d-none" accept="image/jpeg,image/jpg,image/png"
                                        required>
                                </div>
                                <div id="imagePreview" class="mt-2"></div>
                                <div id="payment-file-error" class="text-danger small mt-2 d-none">
                                    Ukuran file maksimal 5 MB.
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info small mt-3 mb-3 py-2">
                            <i class="bi bi-info-circle me-1"></i>
                            Pastikan bukti transfer sesuai nominal dan rekening tujuan yang tertera.
                        </div>

                        <div class="d-grid">
                            <button type="submit" id="submitBtn"
                                class="btn btn-lg fw-semibold text-white"
                                style="background:linear-gradient(135deg,#16a085,#0f7a68)">
                                <i class="bi bi-send me-2"></i> Kirim Bukti Transfer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>

        {{-- KANAN: Info Rekening + Riwayat --}}
        <div class="col-lg-4">

            {{-- Informasi Rekening Tujuan --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header fw-semibold" style="background:#fff8e1">
                    <i class="bi bi-bank me-2 text-warning"></i> Informasi Rekening Tujuan
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="text-muted small">Nama Bank</div>
                        <div class="fw-bold fs-5">{{ $bankInfo['bank_name'] }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small">Nomor Rekening</div>
                        <div class="fw-bold fs-5">{{ $bankInfo['account_number'] }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small">Atas Nama</div>
                        <div class="fw-bold fs-5">{{ $bankInfo['account_holder'] }}</div>
                    </div>
                    <div class="alert alert-info small py-2 mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Silakan transfer sesuai nominal yang tertera, kemudian upload bukti transfer di form sebelah kiri.
                    </div>
                </div>
            </div>

            {{-- Riwayat Pembayaran --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header fw-semibold" style="background:#fff3cd">
                    <i class="bi bi-clock-history me-2 text-warning"></i> Riwayat Pembayaran
                </div>
                <div class="card-body">
                    @forelse($order->payments as $payment)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <span class="badge {{ $payment->status == 'success' ? 'bg-success' : ($payment->status == 'pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                {{ $payment->payment_type == 'dp' ? 'DP 50%' : 'Lunas' }} — {{ ucfirst($payment->status) }}
                            </span>
                            <div class="text-muted small mt-1">{{ $payment->created_at->format('d M Y, H:i') }}</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-semibold small">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                            <a href="{{ route('payments.detail', [$order->id, $payment->id]) }}" class="btn btn-sm btn-outline-primary mt-1">
                                <i class="bi bi-eye me-1"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted small mb-0 text-center">Belum ada riwayat pembayaran</p>
                    @endforelse

                    <div class="alert alert-light small mt-3 mb-0 py-2 border">
                        <i class="bi bi-info-circle me-1 text-muted"></i>
                        Setelah melakukan pembayaran, silakan upload bukti transfer di form sebelah kiri agar pesanan dapat segera kami proses.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Drop zone click
    document.getElementById('dropZone').addEventListener('click', function() {
        document.getElementById('proofFile').click();
    });

    // Drag over
    document.getElementById('dropZone').addEventListener('dragover', function(e) {
        e.preventDefault();
        this.style.background = '#f0faf8';
    });
    document.getElementById('dropZone').addEventListener('dragleave', function() {
        this.style.background = '';
    });
    document.getElementById('dropZone').addEventListener('drop', function(e) {
        e.preventDefault();
        this.style.background = '';
        const file = e.dataTransfer.files[0];
        if (file) {
            document.getElementById('proofFile').files = e.dataTransfer.files;
            showPreview(file);
        }
    });

    // File input change
    // File input change
    document.getElementById('proofFile').addEventListener('change', function(e) {
        const file = e.target.files[0];

        if (!file) return;

        // Maksimal 5 MB
        const error = document.getElementById('payment-file-error');

        error.classList.add('d-none');

        if (file.size > 5 * 1024 * 1024) {

            error.classList.remove('d-none');

            this.value = '';
            document.getElementById('imagePreview').innerHTML = '';

            return;
        }

        showPreview(file);
    });

    function showPreview(file) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('imagePreview').innerHTML =
                `<img src="${e.target.result}" class="img-thumbnail" style="max-height:180px">`;
        };
        reader.readAsDataURL(file);
    }

    // Submit loading state
    document.getElementById('paymentForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Mengunggah...';
    });
</script>
@endpush
@endsection