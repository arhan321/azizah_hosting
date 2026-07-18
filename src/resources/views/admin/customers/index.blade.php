@extends('layouts.admin')
@section('title', 'Kelola Pelanggan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Kelola Pelanggan</h4>
        <small class="text-muted">{{ $customers->total() }} pelanggan terdaftar</small>
    </div>
</div>

<form method="GET" class="row g-2 mb-4">
    <div class="col-md-4">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama / email / telepon..."
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
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Total Pesanan</th>
                    <th>Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr>
                    <td class="fw-semibold">{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td><span class="badge bg-primary">{{ $customer->orders_count }}</span></td>
                    <td><small class="text-muted">{{ $customer->created_at->format('d/m/Y') }}</small></td>
                    <td>
                        <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-sm btn-outline-dark">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada pelanggan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($customers->hasPages())
    <div class="card-footer">{{ $customers->links() }}</div>
    @endif
</div>
@endsection
