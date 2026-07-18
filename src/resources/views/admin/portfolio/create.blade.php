@extends('layouts.admin')
@section('title', 'Tambah Portofolio')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.portfolio.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tambah Portofolio Baru</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.portfolio.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title') }}" required>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gambar <span class="text-danger">*</span></label>
                        <input
                            type="file"
                            id="portfolioImage"
                            name="image"
                            class="form-control @error('image') is-invalid @enderror"
                            accept="image/jpeg,image/png,image/webp"
                            required>
                        <div class="form-text">
                            Format: JPG, JPEG, PNG. Maksimal ukuran file 5 MB.
                        </div>

                        <div id="portfolio-image-error" class="text-danger small mt-1 d-none">
                            Ukuran gambar maksimal 5 MB.
                        </div>
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="category_id" class="form-select">
                            <option value="">-- Pilih Kategori --</option>

                            @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Klien</label>
                        <input type="text" name="client_name" class="form-control @error('client_name') is-invalid @enderror"
                            value="{{ old('client_name') }}">
                        @error('client_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                            value="{{ old('location') }}">
                        @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="completion_date" class="form-control @error('completion_date') is-invalid @enderror"
                            value="{{ old('completion_date') }}">
                        @error('completion_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" name="order" class="form-control @error('order') is-invalid @enderror"
                            value="{{ old('order', 0) }}">
                        <small class="text-muted">Semakin kecil, semakin di depan</small>
                        @error('order')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_featured" value="1" class="form-check-input"
                                id="is_featured" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Tandai sebagai Featured
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan
                </button>
                <a href="{{ route('admin.portfolio.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const input = document.getElementById('portfolioImage');
        const error = document.getElementById('portfolio-image-error');

        if (!input) return;

        input.addEventListener('change', function() {

            error.classList.add('d-none');

            const maxSize = 5 * 1024 * 1024;

            if (this.files.length > 0 && this.files[0].size > maxSize) {

                error.classList.remove('d-none');

                this.value = '';
            }

        });

    });
</script>
@endpush