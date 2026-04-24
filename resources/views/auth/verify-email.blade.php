@extends('layouts.guest')

@section('title', 'Verify Email')
@section('header_description', 'Verify your email address')

@section('content')
    @if (session('resent'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill me-2"></i>
            A fresh verification link has been sent to your email address.
        </div>
    @endif

    <div class="alert alert-info">
        <i class="bi bi-info-circle-fill me-2"></i>
        Thanks for signing up! Before getting started, please verify your email address.
    </div>

    <p class="mb-3">
        Didn't receive the email?
    </p>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-envelope-paper me-2"></i>Resend Verification Email
        </button>
    </form>

    <hr>

    <div class="text-center">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-link bg-transparent border-0">
                <i class="bi bi-box-arrow-right me-1"></i>Logout
            </button>
        </form>
    </div>
@endsection