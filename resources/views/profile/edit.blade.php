@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="page-header">
        <h2><i class="bi bi-person me-2"></i>{{ __('Profile') }}</h2>
        <p class="text-muted mb-0">{{ __('Manage your account settings') }}</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Update Profile Information -->
            <div class="stat-card mb-4">
                <h5 class="mb-3">{{ __('Profile Information') }}</h5>
                <p class="text-muted small mb-4">{{ __("Update your account's profile information and email address.") }}
                </p>

                <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                    @csrf
                    @method('patch')

                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Name') }}</label>
                        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Email') }}</label>
                        <input id="email" name="email" type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}" required autocomplete="username">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                            <div class="alert alert-info mt-3">
                                <p class="mb-0">
                                    {{ __('Your email address is unverified.') }}
                                    <button form="send-verification"
                                        class="btn btn-link p-0">{{ __('Click here to re-send the verification email.') }}</button>
                                </p>
                                @if (session('status') === 'verification-link-sent')
                                    <p class="mt-2 text-success mb-0">
                                        {{ __('A new verification link has been sent to your email address.') }}</p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>{{ __('Save') }}
                        </button>

                        @if (session('status') === 'profile-updated')
                            <div class="text-success">
                                <i class="bi bi-check-circle-fill me-1"></i>{{ __('Saved.') }}
                            </div>
                        @endif
                    </div>
                </form>

                <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-none">
                    @csrf
                </form>
            </div>

            <!-- Update Password -->
            <div class="stat-card mb-4">
                <h5 class="mb-3">{{ __('Update Password') }}</h5>
                <p class="text-muted small mb-4">
                    {{ __('Ensure your account is using a long, random password to stay secure.') }}</p>

                <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                    @csrf
                    @method('put')

                    <div class="mb-3">
                        <label for="update_password_current_password"
                            class="form-label">{{ __('Current Password') }}</label>
                        <input id="update_password_current_password" name="current_password" type="password"
                            class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                            autocomplete="current-password">
                        @error('current_password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="update_password_password" class="form-label">{{ __('New Password') }}</label>
                        <input id="update_password_password" name="password" type="password"
                            class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                            autocomplete="new-password">
                        @error('password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="update_password_password_confirmation"
                            class="form-label">{{ __('Confirm Password') }}</label>
                        <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                            class="form-control" autocomplete="new-password">
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-key me-2"></i>{{ __('Save') }}
                        </button>

                        @if (session('status') === 'password-updated')
                            <div class="text-success">
                                <i class="bi bi-check-circle-fill me-1"></i>{{ __('Saved.') }}
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Delete Account Card -->
            <div class="stat-card">
                <h5 class="mb-3 text-danger">{{ __('Delete Account') }}</h5>
                <p class="text-muted small mb-4">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                </p>

                <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal"
                    data-bs-target="#confirmUserDeletionModal">
                    <i class="bi bi-trash me-2"></i>{{ __('Delete Account') }}
                </button>
            </div>

            <!-- Account Info Card -->
            <div class="stat-card mt-4">
                <h5 class="mb-3">{{ __('Account Information') }}</h5>
                <hr>
                <div class="mb-2">
                    <small class="text-muted">{{ __('Member since') }}</small>
                    <p class="mb-0">{{ $user->created_at->format('F d, Y') }}</p>
                </div>
                <div class="mb-2">
                    <small class="text-muted">{{ __('Account ID') }}</small>
                    <p class="mb-0">#{{ $user->id }}</p>
                </div>
                <div>
                    <small class="text-muted">{{ __('Role') }}</small>
                    <p class="mb-0">
                        <span class="badge bg-primary">{{ $user->role ?? 'User' }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header">
                        <h5 class="modal-title text-danger" id="confirmUserDeletionModalLabel">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ __('Delete Account') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <h6>{{ __('Are you sure you want to delete your account?') }}</h6>
                        <p class="text-muted small">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                        </p>

                        <div class="mb-3 mt-4">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input id="password" name="password" type="password"
                                class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                                placeholder="{{ __('Enter your password') }}">
                            @error('password', 'userDeletion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-2"></i>{{ __('Delete Account') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto-hide success messages after 3 seconds
        setTimeout(() => {
            const successMessages = document.querySelectorAll('.text-success');
            successMessages.forEach(msg => {
                setTimeout(() => {
                    msg.style.opacity = '0';
                    setTimeout(() => msg.remove(), 500);
                }, 2000);
            });
        }, 1000);
    </script>
@endpush