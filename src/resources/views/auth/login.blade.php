@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')

<h2 class="auth-title">
    MASUK KE AKUN ANDA
</h2>

@if(session('status'))
<div class="alert alert-success">
    {{ session('status') }}
</div>
@endif

@if($errors->any())
<div class="alert alert-danger">
    {{ $errors->first() }}
</div>
@endif

<form method="POST" action="{{ route('login') }}">

    @csrf

    <!-- Email -->
    <div class="mb-3">

        <label for="email" class="form-label">
            Email
        </label>

        <div class="input-group">

            <span class="input-group-text">
                <i class="bi bi-person"></i>
            </span>

            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror"
                placeholder="Masukan Email Anda"
                required
                autofocus
                oninvalid="this.setCustomValidity('Please fill out this field.')"
                oninput="this.setCustomValidity('')">

            @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror

        </div>

    </div>

    <!-- Password -->
    <div class="mb-3">

        <label for="password" class="form-label">
            Password
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
                placeholder="Masukan Password"
                required
                oninvalid="this.setCustomValidity('Please fill out this field.')"
                oninput="this.setCustomValidity('')">

            <button
                type="button"
                class="input-group-text"
                onclick="togglePassword()">

                <i id="toggleIcon" class="bi bi-eye"></i>

            </button>

            @error('password')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror

        </div>

    </div>

    <!-- Remember -->
    <div class="remember-row">

        <label class="d-flex align-items-center gap-1">

            <input
                class="form-check-input"
                type="checkbox"
                name="remember">

            Inget Saya

        </label>

        @if(Route::has('password.request'))

        <a href="{{ route('password.request') }}">
            Lupa Password?
        </a>

        @endif

    </div>

    <!-- Button -->
    <button
        type="submit"
        class="btn-login">

        <i class="bi bi-box-arrow-in-right"></i>

        Masuk

    </button>

    <!-- Register -->
    <div class="register-text">

        Belum punya akun?

        <a href="{{ route('register') }}">
            Daftar sekarang
        </a>

    </div>

</form>
<script>
    function togglePassword() {
        let password = document.getElementById('password');
        let icon = document.getElementById('toggleIcon');

        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
@endsection