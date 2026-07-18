@extends('layouts.admin')
@section('title', 'Kelola Pembayaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Kelola Pembayaran</h4>
        <small class="text-muted">{{ $payments->total() }} transaksi</small>
    </div>
</div>

<form method="GET" class="row g-2 mb-4">
    <div class="col-md-2">
        <select name="status" class="form-select form-select-sm">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Sukses</option>
            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
        </select>
    </div>
    <div class="col-md-2">
        <select name="verification_status" class="form-select form-select-sm">
            <option value="">Semua Verifikasi</option>
            <option value="pending" {{ request('verification_status') == 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
            <option value="verified" {{ request('verification_status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
            <option value="rejected" {{ request('verification_status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
        </select>
    </div>
    <div class="col-md-4">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari pelanggan..."
               value="{{ request('search') }}">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-sm btn-secondary">Filter</button>
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#Pembayaran</th>
                    <th>Pelanggan</th>
                    <th>Tipe</th>
                    <th>Jumlah</th>
                    <th>Info Transfer</th>
                    <th>Status</th>
                    <th>Verifikasi</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td class="text-muted">#{{ $payment->id }}</td>
                    <td>
                        <div class="fw-semibold">{{ $payment->order->user->name }}</div>
                        <small class="text-muted">Pesanan #{{ $payment->order_id }}</small>
                    </td>
                    <td>{{ $payment->payment_type == 'dp' ? 'DP 50%' : 'Lunas' }}</td>
                    <td class="fw-bold text-success">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td>
                        @if($payment->payment_method == 'bank_transfer')
                            <div class="small">
                                <div><strong>{{ $payment->bank_name ?? '-' }}</strong></div>
                                <div>{{ $payment->account_holder ?? '-' }}</div>
                                <div class="text-muted">{{ $payment->account_number ?? '-' }}</div>
                                @if($payment->payment_proof)
                                <a href="{{ $payment->payment_proof_url }}"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-primary mt-1">
                                    <i class="bi bi-image"></i> Lihat Bukti
                                </a>
                                @endif
                            </div>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $payment->status == 'success' ? 'bg-success' : ($payment->status == 'pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                            {{ $payment->status }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $payment->verification_status == 'verified' ? 'bg-success' : ($payment->verification_status == 'pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                            {{ $payment->verification_status }}
                        </span>
                        @if($payment->verified_at)
                        <div class="small text-muted mt-1">
                            oleh {{ $payment->verifiedBy->name ?? '-' }}
                        </div>
                        @endif
                    </td>
                    <td>
                        <small>
                            @if($payment->transfer_date)
                                Transfer: {{ $payment->transfer_date->format('d/m/Y') }}<br>
                            @endif
                            {{ $payment->created_at->format('d/m/Y H:i') }}
                        </small>
                    </td>
                    <td>
                        @if($payment->verification_status == 'pending')
                        <div class="btn-group-vertical" role="group">
                            <form method="POST" action="{{ route('admin.payments.confirm', $payment->id) }}"
                                  onsubmit="return confirm('Verifikasi pembayaran ini?')" class="mb-1">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success w-100">
                                    <i class="bi bi-check-circle"></i> Verifikasi
                                </button>
                            </form>
                            <button type="button" class="btn btn-sm btn-danger w-100"
                                    data-bs-toggle="modal"
                                    data-bs-target="#rejectModal{{ $payment->id }}">
                                <i class="bi bi-x-circle"></i> Tolak
                            </button>
                        </div>

                        {{-- Modal Tolak --}}
                        <div class="modal fade" id="rejectModal{{ $payment->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('admin.payments.reject', $payment->id) }}">
                                        @csrf @method('PATCH')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tolak Pembayaran #{{ $payment->id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                                <textarea name="notes" class="form-control" rows="3" required
                                                          placeholder="Contoh: Bukti transfer tidak jelas, nominal tidak sesuai, dll"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Tolak Pembayaran</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @else
                        <span class="text-muted small">—</span>
                        @if($payment->verification_notes)
                        <div class="small text-muted" style="max-width: 150px;">
                            <i class="bi bi-info-circle"></i> {{ Str::limit($payment->verification_notes, 30) }}
                        </div>
                        @endif
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-muted py-4">Tidak ada data pembayaran</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
    <div class="card-footer">{{ $payments->links() }}</div>
    @endif
</div>
@endsection
