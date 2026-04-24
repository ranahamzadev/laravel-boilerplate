@extends('layouts.guest')

@section('title', 'Login')
@section('header_description', 'Sign in to your account')

@section('content')
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-envelope"></i>
                </span>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                    value="{{ old('email') }}" required autofocus>
            </div>
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-lock"></i>
                </span>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                    name="password" required>
            </div>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label" for="remember">Remember Me</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
        </button>

        <hr>

        <div class="text-center">
            <a href="{{ route('password.request') }}" class="btn-link">Forgot Password?</a>
            <span class="mx-2 text-muted">|</span>
            <a href="{{ route('register') }}" class="btn-link">Create Account</a>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        // Page-specific JavaScript
        console.log('Login page loaded');
    </script>
@endpush