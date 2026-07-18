@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')

<h2 class="auth-title">
    RESET PASSWORD
</h2>

<form method="POST" action="{{ route('password.store') }}">
    @csrf

    <!-- Password Reset Token -->
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <!-- Email Address -->
    <div class="mb-3">
        <label for="email" class="form-label">
            Email
        </label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-envelope"></i>
            </span>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email', $request->email) }}"
                class="form-control @error('email') is-invalid @enderror"
                placeholder="Masukan Email Anda"
                required
                autofocus
                autocomplete="username"
            >
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Password -->
    <div class="mb-3">
        <label for="password" class="form-label">
            Password Baru
        </label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-lock"></i>
            </span>
            <input
                id="password"
                type="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="Masukan Password Baru"
                required
                autocomplete="new-password"
            >
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Confirm Password -->
    <div class="mb-4">
        <label for="password_confirmation" class="form-label">
            Konfirmasi Password Baru
        </label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-lock-fill"></i>
            </span>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                class="form-control @error('password_confirmation') is-invalid @enderror"
                placeholder="Ulangi Password Baru"
                required
                autocomplete="new-password"
            >
            @error('password_confirmation')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Submit Button -->
    <button
        type="submit"
        class="btn-login"
    >
        <i class="bi bi-shield-lock-fill"></i>
        Reset Password
    </button>
</form>

@endsection
