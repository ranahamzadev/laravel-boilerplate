@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
<div class="page-header">
    <h2><i class="bi bi-shield-lock me-2"></i>Edit Role: {{ $role->name }}</h2>
    <p class="text-muted mb-0">Modify role permissions and details</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="stat-card">
            <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="name" class="form-label">Role Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $role->name) }}" 
                           {{ $role->name === 'super-admin' ? 'disabled' : '' }} required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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
                                               id="perm_{{ $permission->id }}"
                                               {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
                                               @if($role->name === 'super-admin') disabled @endif>
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
                    @if($role->name !== 'super-admin')
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Update Role
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="stat-card">
            <h5><i class="bi bi-users me-2"></i>Users with this role</h5>
            <hr>
            @php $usersWithRole = \App\Models\User::role($role->name)->get(); @endphp
            @if($usersWithRole->count() > 0)
                @foreach($usersWithRole as $user)
                <div class="mb-2">
                    <i class="bi bi-person-circle me-2"></i>
                    {{ $user->name }} ({{ $user->email }})
                </div>
                @endforeach
            @else
                <p class="text-muted">No users assigned to this role</p>
            @endif
        </div>
    </div>
</div>
@endsection