<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Aqlam Mural Kaligrafi'))</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Nunito:wght@400;500;600&display=swap"
        rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {

            font-family: 'Nunito', sans-serif;

            background: linear-gradient(rgba(255, 255, 255, 0.60),
                rgba(255, 255, 255, 0.60)),
            url("{{ asset('img/hero-section.jpeg') }}");

            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;

            min-height: 100vh;
        }

        .auth-wrapper {

            min-height: 100vh;

            display: flex;
            justify-content: center;
            align-items: center;

            padding: 20px;
        }

        .auth-card {

            width: 100%;
            max-width: 420px;

            background: #f7f7f7;

            border-radius: 24px;

            overflow: hidden;

            box-shadow:
                0 6px 18px rgba(0, 0, 0, 0.18);
        }

        .auth-header {

            text-align: center;

            padding: 14px 20px 12px;

            border-bottom: 1px solid #cfcfcf;
        }

        .logo-img {

            width: 60px;

            margin-bottom: 4px;
        }

        .brand-title {

            font-family: 'Cinzel', serif;

            font-size: 1.4rem;

            font-weight: 700;

            color: #000;

            margin: 0;
        }

        .auth-body {

            padding: 28px 30px 26px;
        }

        .auth-title {

            font-family: 'Cinzel', serif;

            text-align: center;

            font-size: 1rem;

            font-weight: 700;

            letter-spacing: 1px;

            color: #000;

            margin-bottom: 24px;
        }

        .form-label {

            font-size: 14px;

            margin-bottom: 6px;

            color: #000;
        }

        .form-control {

            height: 40px;

            border: 1px solid #bdbdbd;

            border-radius: 0;

            font-size: 14px;

            background: #fff;

            box-shadow:
                0 3px 6px rgba(0, 0, 0, 0.12);
        }

        .form-control:focus {

            border-color: #bdbdbd;

            box-shadow:
                0 3px 6px rgba(0, 0, 0, 0.12);
        }

        .form-control:focus {

            border-color: #39b7c2 !important;

            box-shadow: none !important;
        }

        .input-group:focus-within .input-group-text {

            border-color: #39b7c2 !important;

            color: #39b7c2;
        }

        .input-group:focus-within .form-control {

            border-color: #39b7c2 !important;
        }

        .input-group-text {

            background: #fff;

            border: 1px solid #bdbdbd;

            border-radius: 0;
        }

        .input-group-text:first-child {
            border-right: none;
        }

        .remember-row {

            display: flex;
            justify-content: space-between;
            align-items: center;

            margin-top: 2px;
            margin-bottom: 26px;

            font-size: 13px;
        }

        .remember-row a {

            color: #000;

            text-decoration: none;

            font-weight: 600;
        }

        .btn-login {

            width: 100% !important;

            height: 42px !important;

            border: none !important;

            border-radius: 30px !important;

            background: #39b7c2 !important;

            color: #fff !important;

            font-size: 15px !important;

            font-weight: 700 !important;

            transition: .3s;
        }

        .btn-login:hover {

            background: #2ea8b2 !important;

            color: #fff !important;
        }

        .register-text {

            text-align: center;

            margin-top: 18px;

            font-size: 13px;

            color: #000;
        }

        .register-text a {

            color: #000;

            text-decoration: none;

            font-weight: 700;
        }

        @media(max-width:576px) {

            .auth-card {
                max-width: 100%;
            }

            .auth-body {
                padding: 24px 20px;
            }

            .brand-title {
                font-size: 1.1rem;
            }

        }
    </style>

</head>

<body>

    <div class="auth-wrapper">

        <div class="auth-card">

            <div class="auth-header">

                <a href="{{ route('home') }}" class="text-decoration-none">

                    <img
                        src="{{ asset('img/logo.PNG') }}"
                        alt="Logo"
                        class="logo-img">

                    <h1 class="brand-title">
                        AQLAM MURAL KALIGRAFI
                    </h1>

                </a>

            </div>

            <div class="auth-body">

                @yield('content')

            </div>

        </div>

    </div>

</body>

</html>