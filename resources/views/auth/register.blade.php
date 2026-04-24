@extends('layouts.guest')

@section('title', 'Register')
@section('header_description', 'Create your free account')

@section('content')
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-person"></i>
                </span>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    value="{{ old('name') }}" required>
            </div>
            @error('name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-envelope"></i>
                </span>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                    value="{{ old('email') }}" required>
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

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-lock-fill"></i>
                </span>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                    required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-person-plus me-2"></i>Register
        </button>

        <hr>

        <div class="text-center">
            <p class="mb-0">Already have an account?
                <a href="{{ route('login') }}" class="btn-link">Sign In</a>
            </p>
        </div>
    </form>
@endsection