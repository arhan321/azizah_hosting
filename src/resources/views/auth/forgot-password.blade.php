@extends('layouts.guest')
@section('title', 'Lupa Password')

@section('content')
<h5 class="fw-bold text-center mb-3">Lupa Password?</h5>
<p class="text-muted small text-center mb-4">Masukkan email Anda dan kami akan mengirimkan link reset password.</p>

@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}"
               class="form-control @error('email') is-invalid @enderror" required autofocus>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="btn btn-gold w-100">
        <i class="bi bi-envelope me-1"></i> Kirim Link Reset Password
    </button>
    <p class="text-center mt-3 mb-0 small">
        <a href="{{ route('login') }}" class="text-decoration-none">Kembali ke halaman masuk</a>
    </p>
</form>
@endsection
