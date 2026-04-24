@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-header">
        <h2><i class="bi bi-speedometer2 me-2"></i>Dashboard</h2>
        <p class="text-muted mb-0">Welcome back, {{ Auth::user()->name }}!</p>
    </div>
@endsection

@push('scripts')
    <script>
        // Dashboard specific scripts
        console.log('Dashboard loaded');
    </script>
@endpush