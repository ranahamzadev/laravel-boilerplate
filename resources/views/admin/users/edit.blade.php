@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <div class="page-header">
        <h2><i class="bi bi-pencil-square me-2"></i>Edit User</h2>
        <p class="text-muted mb-0">Update user information and roles</p>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="stat-card">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $user->name) }}" required>
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
                        <label for="roles" class="form-label">Roles (Select Multiple)</label>
                        <select class="form-select select2-multi @error('roles') is-invalid @enderror" id="roles"
                            name="roles[]" multiple="multiple" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}"
                                    {{ in_array($role->name, old('roles', $userRoles)) ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('roles')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">You can select multiple roles. Hold Ctrl (Windows) or Cmd (Mac) to select
                            multiple.</div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">New Password (Optional)</label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Leave blank to keep current password. Minimum 8 characters.</div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation">
                            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back
                        </a>
                        <button type="submit" class="btn btn-outline-warning">
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
                        @if ($user->email_verified_at)
                            <span class="badge bg-success">Verified at
                                {{ $user->email_verified_at->format('M d, Y') }}</span>
                        @else
                            <span class="badge bg-warning">Not Verified</span>
                        @endif
                    </p>
                </div>

                <hr>
                <h6>Current Roles:</h6>
                <div class="d-flex flex-wrap gap-1 mb-3">
                    @forelse($userRoles as $role)
                        <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="bi bi-shield-check me-1"></i>{{ ucfirst($role) }}
                        </span>
                    @empty
                        <span class="text-muted">No roles assigned</span>
                    @endforelse
                </div>

                <hr>
                <div class="password-strength mt-3" id="passwordStrength" style="display: none;">
                    <small>Password Strength:</small>
                    <div class="progress mt-1" style="height: 5px;">
                        <div class="progress-bar" id="strengthBar" role="progressbar" style="width: 0%"></div>
                    </div>
                    <small id="strengthText" class="text-muted"></small>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#roles').select2({
                    theme: 'default',
                    placeholder: 'Select roles',
                    allowClear: false,
                    width: '100%'
                });

                const togglePassword = document.getElementById('togglePassword');
                const password = document.getElementById('password');

                if (togglePassword && password) {
                    togglePassword.addEventListener('click', function() {
                        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                        password.setAttribute('type', type);
                        this.querySelector('i').classList.toggle('bi-eye');
                        this.querySelector('i').classList.toggle('bi-eye-slash');
                    });
                }

                const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
                const confirmPassword = document.getElementById('password_confirmation');

                if (toggleConfirmPassword && confirmPassword) {
                    toggleConfirmPassword.addEventListener('click', function() {
                        const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                        confirmPassword.setAttribute('type', type);
                        this.querySelector('i').classList.toggle('bi-eye');
                        this.querySelector('i').classList.toggle('bi-eye-slash');
                    });
                }
            });
        </script>
    @endpush
@endsection
