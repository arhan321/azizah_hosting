@extends('layouts.admin')
@section('title', 'Edit Portofolio')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.portfolio.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Portofolio</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.portfolio.update', $portfolio) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title', $portfolio->title) }}" required>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $portfolio->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gambar</label>
                        @if($portfolio->image_url)
                        <div class="mb-2">
                            <img src="{{ $portfolio->image_url }}" class="img-thumbnail" style="max-width:200px">
                        </div>
                        @endif
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" 
                               accept="image/*">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar. Format: JPG, PNG, WEBP. Maksimal 5MB</small>
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
                {{ old('category_id', $portfolio->category_id) == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>
                    <div class="mb-3">
                        <label class="form-label">Nama Klien</label>
                        <input type="text" name="client_name" class="form-control @error('client_name') is-invalid @enderror" 
                               value="{{ old('client_name', $portfolio->client_name) }}">
                        @error('client_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" 
                               value="{{ old('location', $portfolio->location) }}">
                        @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="completion_date" class="form-control @error('completion_date') is-invalid @enderror" 
                               value="{{ old('completion_date', $portfolio->completion_date?->format('Y-m-d')) }}">
                        @error('completion_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" name="order" class="form-control @error('order') is-invalid @enderror" 
                               value="{{ old('order', $portfolio->order) }}">
                        <small class="text-muted">Semakin kecil, semakin di depan</small>
                        @error('order')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_featured" value="1" class="form-check-input" 
                                   id="is_featured" {{ old('is_featured', $portfolio->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Tandai sebagai Featured
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Update
                </button>
                <a href="{{ route('admin.portfolio.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
