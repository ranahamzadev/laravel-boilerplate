@extends('layouts.app')

@section('title', 'Roles')

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="bi bi-shield-lock me-2"></i>Roles Management</h2>
                <p class="text-muted mb-0">Manage user roles and permissions</p>
            </div>
            @can('create roles')
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Create New Role
                </a>
            @endcan
        </div>
    </div>

    <div class="stat-card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Role Name</th>
                        <th>Permissions</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $role->name }}</span>
                            </td>
                            <td>
                                @foreach($role->permissions->take(3) as $permission)
                                    <span class="badge bg-secondary me-1">{{ $permission->name }}</span>
                                @endforeach
                                @if($role->permissions->count() > 3)
                                    <span class="badge bg-info">+{{ $role->permissions->count() - 3 }} more</span>
                                @endif
                            </td>
                            <td>{{ $role->created_at->format('M d, Y') }}</td>
                            <td>
                                @can('edit roles')
                                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endcan
                                @can('delete roles')
                                    @if($role->name !== 'super-admin')
                                        <button type="button" class="btn btn-sm btn-danger delete-role-btn" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal" data-role-id="{{ $role->id }}"
                                            data-role-name="{{ $role->name }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No roles found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $roles->links() }}
        </div>
    </div>

    <!-- Single Delete Modal (Outside the loop) -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title text-danger" id="deleteModalLabel">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>Delete Role
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete the role <strong id="roleName"></strong>?</p>
                        <p class="text-danger">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-2"></i>Delete Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Handle delete modal data
            document.addEventListener('DOMContentLoaded', function () {
                const deleteButtons = document.querySelectorAll('.delete-role-btn');
                const deleteForm = document.getElementById('deleteForm');
                const roleNameSpan = document.getElementById('roleName');

                deleteButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const roleId = this.getAttribute('data-role-id');
                        const roleName = this.getAttribute('data-role-name');

                        // Update the form action URL
                        deleteForm.action = `/admin/roles/${roleId}`;

                        // Update the role name in the modal
                        roleNameSpan.textContent = roleName;
                    });
                });
            });
        </script>
    @endpush
@endsection