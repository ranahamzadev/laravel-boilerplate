@extends('layouts.guest')

@section('title', 'Forgot Password')
@section('header_description', 'Reset your password')

@section('content')
    <form method="POST" action="{{ route('password.email') }}">
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

        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Enter your email address and we'll send you a link to reset your password.
        </div>

        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-envelope-paper me-2"></i>Send Reset Link
        </button>

        <hr>

        <div class="text-center">
            <a href="{{ route('login') }}" class="btn-link">
                <i class="bi bi-arrow-left me-1"></i>Back to Login
            </a>
        </div>
    </form>
@endsection