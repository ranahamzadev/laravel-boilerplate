@extends('layouts.guest')

@section('title', 'Confirm Password')
@section('header_description', 'Security verification')

@section('content')
    <div class="alert alert-warning">
        <i class="bi bi-shield-exclamation me-2"></i>
        Please confirm your password before continuing.
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
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

        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-check-circle me-2"></i>Confirm Password
        </button>
    </form>
@endsection