@extends('layouts.app')

@section('title', 'Create Role')

@section('content')
<div class="page-header">
    <h2><i class="bi bi-shield-lock me-2"></i>Create New Role</h2>
    <p class="text-muted mb-0">Add a new role to the system</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="stat-card">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="name" class="form-label">Role Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Use lowercase with hyphens (e.g., content-manager)</div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Assign Permissions</label>
                    <div class="row">
                        @foreach($permissions->groupBy(function($item) {
                            return explode(' ', $item->name)[0];
                        }) as $group => $groupPermissions)
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <strong>{{ ucfirst($group) }}</strong>
                                </div>
                                <div class="card-body">
                                    @foreach($groupPermissions as $permission)
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" 
                                               name="permissions[]" 
                                               value="{{ $permission->id }}" 
                                               id="perm_{{ $permission->id }}">
                                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Create Role
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="stat-card">
            <h5><i class="bi bi-info-circle me-2"></i>Role Information</h5>
            <hr>
            <p class="small text-muted">Roles are used to group permissions together. Each user can be assigned one or more roles.</p>
            <p class="small text-muted">Example roles: admin, editor, viewer, manager</p>
            <div class="alert alert-info">
                <i class="bi bi-lightbulb me-2"></i>
                Tip: Use descriptive names for roles
            </div>
        </div>
    </div>
</div>
@endsection