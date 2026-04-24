@extends('layouts.guest')

@section('title', 'Reset Password')
@section('header_description', 'Create new password')

@section('content')
    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-envelope"></i>
                </span>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                    value="{{ $email ?? old('email') }}" required readonly>
            </div>
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-lock"></i>
                </span>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                    name="password" required autofocus>
            </div>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm New Password</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-lock-fill"></i>
                </span>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                    required>
            </div>
        </div>

        <div class="alert alert-warning">
            <i class="bi bi-shield-lock me-2"></i>
            Password must be at least 8 characters long.
        </div>

        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-check-circle me-2"></i>Reset Password
        </button>

        <hr>

        <div class="text-center">
            <a href="{{ route('login') }}" class="btn-link">
                <i class="bi bi-box-arrow-in-right me-1"></i>Back to Login
            </a>
        </div>
    </form>
@endsection