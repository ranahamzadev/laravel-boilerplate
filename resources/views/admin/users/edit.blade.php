@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <div class="page-header">
        <h2><i class="bi bi-pencil-square me-2"></i>Edit User</h2>
        <p class="text-muted mb-0">Update user information and role</p>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="stat-card">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                            value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role', $userRole->name ?? '') == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">New Password (Optional)</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                            name="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Leave blank to keep current password. Minimum 8 characters.</div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <h5><i class="bi bi-clock-history me-2"></i>User Information</h5>
                <hr>
                <div class="mb-2">
                    <small class="text-muted">Account Created</small>
                    <p class="mb-0">{{ $user->created_at->format('F d, Y h:i A') }}</p>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Last Updated</small>
                    <p class="mb-0">{{ $user->updated_at->format('F d, Y h:i A') }}</p>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Email Status</small>
                    <p class="mb-0">
                        @if($user->email_verified_at)
                            <span class="badge bg-success">Verified at {{ $user->email_verified_at->format('M d, Y') }}</span>
                        @else
                            <span class="badge bg-warning">Not Verified</span>
                        @endif
                    </p>
                </div>
                <hr>
                <div class="alert alert-warning">
                    <i class="bi bi-shield-exclamation me-2"></i>
                    <strong>Note:</strong> Changing a user's role will update their access permissions immediately.
                </div>
            </div>
        </div>
    </div>
@endsection