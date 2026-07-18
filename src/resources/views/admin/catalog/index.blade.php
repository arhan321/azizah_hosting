@extends('layouts.admin')
@section('title', 'Kelola Desain')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Kelola Desain Katalog</h4>
        <small class="text-muted">{{ $designs->total() }} desain terdaftar</small>
    </div>
    <a href="{{ route('admin.catalog.create') }}" class="btn btn-dark">
        <i class="bi bi-plus me-1"></i> Tambah Desain
    </a>
</div>

{{-- Filter --}}
<form method="GET" class="row g-2 mb-4">
    <div class="col-md-3">
        <select name="category" class="form-select form-select-sm">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari desain..." value="{{ request('search') }}">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-sm btn-secondary w-100">Filter</button>
    </div>
    <div class="col-md-2">
        <a href="{{ route('admin.catalog.categories.index') }}" class="btn btn-sm btn-outline-secondary w-100">
            <i class="bi bi-tags me-1"></i> Kategori
        </a>
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th width="60">Gambar</th>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($designs as $design)
                <tr>
                    <td>
                        @if($design->image_url)
                            <img src="{{ $design->image_url }}" class="rounded" style="width:50px;height:45px;object-fit:cover">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:50px;height:45px">
                                <i class="bi bi-image text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $design->name }}</div>
                        <small class="text-muted">{{ $design->slug }}</small>
                    </td>
                    <td>
                        @if($design->description)
                            <div class="rich-text-preview small text-muted">{!! $design->description !!}</div>
                        @else
                            <small class="text-muted">-</small>
                        @endif
                    </td>
                    <td><span class="badge bg-warning text-dark">{{ $design->category->name }}</span></td>
                    <td class="fw-bold text-success">Rp {{ number_format($design->price, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('admin.catalog.edit', $design->id) }}" class="btn btn-sm btn-outline-primary me-1">Edit</a>
                        <form method="POST" action="{{ route('admin.catalog.destroy', $design->id) }}" class="d-inline"
                              onsubmit="return confirm('Hapus desain ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada desain</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($designs->hasPages())
    <div class="card-footer">{{ $designs->links() }}</div>
    @endif
</div>

@push('styles')
<style>
    .rich-text-preview {
        max-width: 280px;
        max-height: 78px;
        overflow: hidden;
    }

    .rich-text-preview p:last-child {
        margin-bottom: 0;
    }
</style>
@endpush
@endsection
