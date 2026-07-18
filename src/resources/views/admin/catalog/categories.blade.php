@extends('layouts.admin')
@section('title', 'Kelola Kategori')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Kelola Kategori</h4>
    <a href="{{ route('admin.catalog.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Desain
    </a>
</div>

<div class="row g-4">
    {{-- Tambah Kategori --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header fw-semibold">Tambah Kategori Baru</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.catalog.categories.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required placeholder="e.g. Kaligrafi Islami">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <button type="submit" class="btn btn-dark w-100">
                        <i class="bi bi-plus me-1"></i> Tambah
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Daftar Kategori --}}
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header fw-semibold">Daftar Kategori</div>
            <ul class="list-group list-group-flush">
                @forelse($categories as $cat)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
    <div class="fw-semibold">{{ $cat->name }}</div>

    <div class="small text-muted mt-1">
        <div>{{ $cat->designs_count }} desain</div>
        <div>{{ $cat->portfolios_count }} portofolio</div>
    </div>
</div>
                    <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal" data-bs-target="#editModal{{ $cat->id }}">
                            Edit
                        </button>
                        @if(($cat->designs_count + $cat->portfolios_count) == 0)
                        <form method="POST" action="{{ route('admin.catalog.categories.destroy', $cat->id) }}"
                              onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                        @endif
                    </div>
                </li>

                {{-- Edit Modal --}}
                <div class="modal fade" id="editModal{{ $cat->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Kategori</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST" action="{{ route('admin.catalog.categories.update', $cat->id) }}">
                                @csrf @method('PUT')
                                <div class="modal-body">
                                    <input type="text" name="name" class="form-control" value="{{ $cat->name }}" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-dark">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <li class="list-group-item text-muted text-center py-3">Belum ada kategori</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
