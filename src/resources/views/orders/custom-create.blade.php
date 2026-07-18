@extends('layouts.app')
@section('title', 'Pesanan Custom Baru')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-1">Form Pesanan Custom</h3>
    <p class="text-muted mb-4">Ceritakan konsep kaligrafi mural yang Anda inginkan</p>

    <form method="POST" action="{{ route('custom-orders.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            <div class="col-lg-8">
                {{-- Detail Konsep --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header text-white fw-semibold" style="background-color:#148f77;">
                        <i class="bi bi-pencil me-2"></i> Detail Pesanan
                    </div>
                    <div class="card-body">

                        {{-- Nama Karya --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama / Desain Karya <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="e.g. Kaligrafi Ayat Kursi" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Jenis Bahan --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih Jenis Bahan <span class="text-danger">*</span></label>
                            <select name="material" class="form-select @error('material') is-invalid @enderror" required>
                                <option value="">-- Pilih Jenis Bahan --</option>
                                <option value="tembok" {{ old('material') == 'tembok' ? 'selected' : '' }}>Tembok</option>
                                <option value="akrilik" {{ old('material') == 'akrilik' ? 'selected' : '' }}>Akrilik</option>
                                <option value="kayu" {{ old('material') == 'kayu' ? 'selected' : '' }}>Kayu</option>
                                <option value="marmer" {{ old('material') == 'marmer' ? 'selected' : '' }}>Marmer</option>
                                <option value="kanvas" {{ old('material') == 'kanvas' ? 'selected' : '' }}>Kanvas</option>
                                <option value="kaca" {{ old('material') == 'kaca' ? 'selected' : '' }}>Kaca</option>
                                <option value="sticker" {{ old('material') == 'sticker' ? 'selected' : '' }}>Sticker</option>
                                <option value="lainnya" {{ old('material') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('material') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Tingkat Ornament --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih Tingkat Ornament <span class="text-danger">*</span></label>
                            <select name="ornament_level" class="form-select @error('ornament_level') is-invalid @enderror" required>
                                <option value="">-- Pilih Tingkat Ornament --</option>
                                <option value="minimal" {{ old('ornament_level') == 'minimal' ? 'selected' : '' }}>
                                    Minimal
                                </option>

                                <option value="easy" {{ old('ornament_level') == 'easy' ? 'selected' : '' }}>
                                    Easy Ornament
                                </option>

                                <option value="expert" {{ old('ornament_level') == 'expert' ? 'selected' : '' }}>
                                    Expert Ornament
                                </option>
                            </select>
                            @error('ornament_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Ukuran --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Lebar Area (m) <span class="text-danger">*</span></label>
                                <input type="number" name="width" class="form-control @error('width') is-invalid @enderror"
                                    value="{{ old('width') }}" placeholder="e.g. 3" step="0.01" min="0.01" required>
                                @error('width') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tinggi Area (m) <span class="text-danger">*</span></label>
                                <input type="number" name="height" class="form-control @error('height') is-invalid @enderror"
                                    value="{{ old('height') }}" placeholder="e.g. 2" step="0.01" min="0.01" required>
                                @error('height') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Preferensi Warna --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Preferensi Warna</label>
                            <input type="text" name="color_preference" class="form-control"
                                value="{{ old('color_preference') }}" placeholder="e.g. Emas & Hijau Tua">
                        </div>

                        {{-- Target Selesai --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Target Selesai</label>
                            <input type="date" name="deadline" class="form-control @error('deadline') is-invalid @enderror"
                                value="{{ old('deadline') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            @error('deadline') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Alamat --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                rows="2" placeholder="Alamat lengkap lokasi pengerjaan..." required>{{ old('address') }}</textarea>
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                    </div>
                </div>

                {{-- Upload File --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header text-white fw-semibold" style="background-color:#148f77;">
                        <i class="bi bi-upload me-2"></i> Upload Foto Lokasi & Referensi
                    </div>
                    <div class="card-body">
                        <input
                            type="file"
                            id="customFiles"
                            name="files[]"
                            class="form-control @error('files.*') is-invalid @enderror"
                            multiple
                            accept="image/jpeg,image/png,application/pdf">

                        <div class="form-text">
                            Format: JPG, PNG, PDF. Maks. 5MB per file. Bisa upload lebih dari satu.
                        </div>

                        <div id="custom-file-error" class="text-danger small mt-1 d-none">
                            Ukuran file maksimal 5 MB per file.
                        </div>
                        @error('files.*')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Sidebar Informasi --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm position-sticky" style="top:80px">
                    <div class="card-header text-white fw-semibold" style="background-color:#148f77;">
                        <i class="bi bi-info-circle me-2"></i> Informasi
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info small">
                            <i class="bi bi-lightbulb me-1"></i>
                            Harga akan ditentukan oleh admin setelah melihat detail konsep Anda.
                        </div>

                        {{-- Brief / Catatan --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Brief / Catatan Tambahan</label>
                            <textarea name="brief" class="form-control" rows="3"
                                placeholder="Detail tambahan, referensi Al-Quran, keinginan khusus...">{{ old('brief') }}</textarea>
                        </div>

                        {{-- Rencana Pembayaran --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Rencana Pembayaran</label>
                            <div class="form-check">

                                <input class="form-check-input" type="radio" name="payment_type" value="full" id="payFull"
                                    {{ old('payment_type','full') == 'full' ? 'checked' : '' }}>
                                <label class="form-check-label" for="payFull">
                                    <strong>Lunas</strong>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_type" value="dp" id="payDP"
                                    {{ old('payment_type') == 'dp' ? 'checked' : '' }}>
                                <label class="form-check-label" for="payDP">
                                    <strong>DP 50%</strong>
                                </label>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn text-white fw-semibold w-100" style="background:#0f8b7b;">
                                <i class="bi bi-send me-1"></i> Kirim Pesanan Custom
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const input = document.getElementById('customFiles');
        const error = document.getElementById('custom-file-error');

        if (!input) return;

        input.addEventListener('change', function() {

            error.classList.add('d-none');

            const maxSize = 5 * 1024 * 1024;

            for (const file of this.files) {

                if (file.size > maxSize) {

                    error.classList.remove('d-none');

                    this.value = '';

                    return;
                }

            }

        });

    });
</script>