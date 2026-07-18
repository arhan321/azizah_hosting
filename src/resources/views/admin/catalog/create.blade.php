@extends('layouts.admin')
@section('title', 'Tambah Desain')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Tambah Desain Baru</h4>
    <a href="{{ route('admin.catalog.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.catalog.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.catalog._form')
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-dark">
                    <i class="bi bi-save me-1"></i> Simpan Desain
                </button>
                <a href="{{ route('admin.catalog.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
