<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
        }

        .guest-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .guest-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
        }

        .guest-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1.5rem;
            text-align: center;
            color: white;
            border-radius: 1rem 1rem 0 0;
        }

        .guest-header h3 {
            margin: 0;
            font-weight: 600;
        }

        .guest-body {
            padding: 2rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 0.6rem;
            font-weight: 500;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        @media (max-width: 576px) {
            .guest-body {
                padding: 1.5rem;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="guest-wrapper">
        <div class="guest-card">
            <div class="guest-header">
                <h3><i class="bi bi-building"></i> {{ config('app.name') }}</h3>
                <small>@yield('header_description')</small>
            </div>

            @if(session('success'))
                <div class="alert alert-success m-3 mb-0">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger m-3 mb-0">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="guest-body">
                @yield('content')
            </div>

            <div class="text-center pb-3">
                <small class="text-muted">&copy; {{ date('Y') }} {{ config('app.name') }}</small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>