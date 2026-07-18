@extends('layouts.admin')
@section('title', 'Kelola Portofolio')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Kelola Portofolio</h4>
    <a href="{{ route('admin.portfolio.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Portofolio
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="80">Gambar</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Klien</th>
                        <th>Lokasi</th>
                        <th>Tanggal</th>
                        <th width="100">Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($portfolios as $portfolio)
                    <tr>
                        <td>
                            @if($portfolio->image_url)
                            <img src="{{ $portfolio->image_url }}" class="img-thumbnail" style="width:60px;height:60px;object-fit:cover">
                            @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="width:60px;height:60px">
                                <i class="bi bi-image text-muted"></i>
                            </div>
                            @endif
                        </td>
                        <td>
    <div class="fw-semibold">{{ $portfolio->title }}</div>
    <small class="text-muted">{{ Str::limit($portfolio->description, 50) }}</small>
</td>

<td>
    @if($portfolio->category)
          <span class="badge bg-warning text-dark">
            {{ $portfolio->category->name }}
        </span>
    @else
        -
    @endif
</td>

<td>{{ $portfolio->client_name ?? '-' }}</td>
                        <td>{{ $portfolio->location ?? '-' }}</td>
                        <td>
                            @if($portfolio->completion_date)
                            {{ $portfolio->completion_date->format('d M Y') }}
                            @else
                            -
                            @endif
                        </td>
                        <td>
                            @if($portfolio->is_featured)
                            <span class="badge bg-warning text-dark">Featured</span>
                            @else
                            <span class="badge bg-light text-dark">Normal</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.portfolio.edit', $portfolio) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" onclick="confirmDelete('{{ $portfolio->id }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            <form id="delete-form-{{ $portfolio->id }}" action="{{ route('admin.portfolio.destroy', $portfolio) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            Belum ada data portofolio
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $portfolios->links() }}
</div>

<script>
function confirmDelete(id) {
    if (confirm('Yakin ingin menghapus portofolio ini?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endsection
