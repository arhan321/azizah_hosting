@extends('layouts.guest')

@section('title', 'Daftar Akun')

@section('content')

<h2 class="auth-title">
    BUAT AKUN BARU
</h2>

<form method="POST" action="{{ route('register') }}">

    @csrf

    <!-- Nama -->
    <div class="mb-3">

        <label for="name" class="form-label">
            Nama Lengkap
        </label>

        <div class="input-group">

            <span class="input-group-text bg-white">
                <i class="bi bi-person"></i>
            </span>

            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                class="form-control @error('name') is-invalid @enderror"
                required
                autofocus
                autocomplete="name"
                placeholder="Nama lengkap Anda">

        </div>

        @error('name')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror

    </div>

    <!-- Email -->
    <div class="mb-3">

        <label for="email" class="form-label">
            Email
        </label>

        <div class="input-group">

            <span class="input-group-text bg-white">
                <i class="bi bi-envelope"></i>
            </span>

            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror"
                required
                autocomplete="username"
                placeholder="email@contoh.com">

        </div>

        @error('email')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror

    </div>

    <!-- Phone -->
    <div class="mb-3">

        <label for="phone" class="form-label">
            No. WhatsApp / Telepon
        </label>

        <div class="input-group">

            <span class="input-group-text bg-white">
                <i class="bi bi-telephone"></i>
            </span>

            <input
                id="phone"
                type="tel"
                name="phone"
                value="{{ old('phone') }}"
                class="form-control @error('phone') is-invalid @enderror"
                required
                placeholder="08xxxxxxxxxx">

        </div>

        @error('phone')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror

    </div>

    <!-- Password -->
    <div class="mb-3">

        <label for="password" class="form-label">
            Password
        </label>

        <div class="input-group">

            <span class="input-group-text bg-white">
                <i class="bi bi-lock"></i>
            </span>

            <input
                id="password"
                type="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                required
                autocomplete="new-password"
                placeholder="Minimal 8 karakter">

            <button
                type="button"
                class="input-group-text bg-white"
                onclick="toggleRegisterPassword('password', 'togglePasswordIcon')"
                aria-label="Tampilkan atau sembunyikan password"
                title="Tampilkan atau sembunyikan password">

                <i id="togglePasswordIcon" class="bi bi-eye"></i>

            </button>

        </div>

        @error('password')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror

    </div>

    <!-- Konfirmasi Password -->
    <div class="mb-4">

        <label for="password_confirmation" class="form-label">
            Konfirmasi Password
        </label>

        <div class="input-group">

            <span class="input-group-text bg-white">
                <i class="bi bi-shield-lock"></i>
            </span>

            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                class="form-control @error('password_confirmation') is-invalid @enderror"
                required
                autocomplete="new-password">

            <button
                type="button"
                class="input-group-text bg-white"
                onclick="toggleRegisterPassword('password_confirmation', 'togglePasswordConfirmationIcon')"
                aria-label="Tampilkan atau sembunyikan konfirmasi password"
                title="Tampilkan atau sembunyikan konfirmasi password">

                <i id="togglePasswordConfirmationIcon" class="bi bi-eye"></i>

            </button>

            @error('password_confirmation')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror

        </div>


        <!-- Button -->
        <button
            type="submit"
            class="btn-login mt-3">

            <i class="bi bi-person-plus"></i>

            Daftar Sekarang

        </button>

        <!-- Login -->
        <div class="register-text">

            Sudah punya akun?

            <a href="{{ route('login') }}">
                Masuk di sini
            </a>

        </div>

</form>

<script>
    function toggleRegisterPassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const toggleIcon = document.getElementById(iconId);

        if (!passwordInput || !toggleIcon) {
            return;
        }

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        }
    }
</script>

@endsection