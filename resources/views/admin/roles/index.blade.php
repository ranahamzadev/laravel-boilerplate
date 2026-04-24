@extends('layouts.app')

@section('title', 'Roles')

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2><i class="bi bi-shield-lock me-2"></i>Roles Management</h2>
                <p class="text-muted mb-0">Manage user roles and their permissions</p>
            </div>
            <div class="d-flex gap-2">
                @can('create-roles')
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Create New Role
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="stat-card">
        <!-- Search and Filter Bar -->
        <div class="row mb-4 g-2">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Search roles by name...">
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-md-end gap-2">
                    <select id="permissionFilter" class="form-select w-auto">
                        <option value="">All Permissions</option>
                        @php
                            $allPermissions = \Spatie\Permission\Models\Permission::all();
                            $uniqueGroups = $allPermissions
                                ->map(function ($item) {
                                    return explode(' ', $item->name)[0];
                                })
                                ->unique();
                        @endphp
                        @foreach ($uniqueGroups as $group)
                            <option value="{{ $group }}">{{ ucfirst($group) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Table -->
        <table class="table table-hover align-middle" id="rolesTable" style="min-width: 800px;">
            <thead class="bg-light">
                <tr>
                    <th width="5%">ID</th>
                    <th width="20%">Role Name</th>
                    <th width="50%">Permissions</th>
                    <th width="15%">Created At</th>
                    <th width="10%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                    <tr data-role-name="{{ strtolower($role->name) }}"
                        data-role-permissions="{{ $role->permissions->pluck('name')->implode(',') }}">
                        <td class="py-3">#{{ $role->id }}</td>
                        <td class="py-3">
                            <div class="d-flex align-items-center">
                                <div class="role-icon-sm me-2 flex-shrink-0">
                                    <i class="bi bi-shield-lock"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ ucfirst($role->name) }}</div>
                                    <small class="text-muted">
                                        @if ($role->name === 'admin')
                                            Full system access
                                        @elseif($role->name === 'manager')
                                            Management access
                                        @else
                                            Limited access
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            <div class="d-flex flex-wrap gap-1">
                                @forelse($role->permissions->take(3) as $permission)
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-check-circle-fill text-success me-1" style="font-size: 0.7rem;"></i>
                                        {{ $permission->name }}
                                    </span>
                                @empty
                                    <span class="text-muted small">No permissions assigned</span>
                                @endforelse
                                @if ($role->permissions->count() > 3)
                                    <span class="badge bg-info">
                                        <i class="bi bi-plus-circle me-1"></i>{{ $role->permissions->count() - 3 }} more
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="py-3">
                            <small class="text-muted" title="{{ $role->created_at->format('F d, Y h:i A') }}">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ $role->created_at->diffForHumans() }}
                            </small>
                        </td>
                        <td class="py-3">
                            @can('edit-roles')
                                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-outline-primary"
                                    title="Edit Role">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @endcan
                            @can('delete-roles')
                                @if ($role->name !== 'admin')
                                    <button type="button" class="btn btn-outline-danger delete-role-btn" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal" data-role-id="{{ $role->id }}"
                                        data-role-name="{{ $role->name }}" title="Delete Role">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="bi bi-shield-slash fs-1 text-muted"></i>
                            <p class="text-muted mt-2 mb-0">No roles found</p>
                            @can('create roles')
                                <a href="{{ route('admin.roles.create') }}" class="btn btn-sm btn-primary mt-3">
                                    <i class="bi bi-plus-circle me-2"></i>Create your first role
                                </a>
                            @endcan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
        <div class="text-muted small">
            Showing {{ $roles->firstItem() ?? 0 }} to {{ $roles->lastItem() ?? 0 }} of {{ $roles->total() }} roles
        </div>
        <div>
            {{ $roles->links() }}
        </div>
    </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header border-0">
                        <h5 class="modal-title text-danger" id="deleteModalLabel">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>Delete Role
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="alert alert-danger">
                            <i class="bi bi-shield-exclamation me-2"></i>
                            <strong>Warning!</strong> This action cannot be undone.
                        </div>
                        <p>Are you sure you want to delete the role:</p>
                        <div class="bg-light p-3 rounded mb-3">
                            <div class="d-flex align-items-center">
                                <div class="role-icon-lg me-3">
                                    <i class="bi bi-shield-lock fs-1"></i>
                                </div>
                                <div>
                                    <strong id="roleName" class="d-block fs-5"></strong>
                                    <small class="text-muted">All permissions assigned to this role will be removed</small>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted small mb-0">This role will be permanently deleted from the system.</p>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-2"></i>Delete Permanently
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .role-icon-sm {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .role-icon-lg {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        /* Fix table spacing and remove horizontal scroll issues */
        .table {
            margin-bottom: 0;
            width: 100%;
        }

        .table> :not(caption)>*>* {
            padding: 0.75rem;
            vertical-align: middle;
        }

        /* Fix table row hover background color */
        .table tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
            cursor: pointer;
        }

        /* Button group styling */
        .btn-group .btn {
            padding: 0.25rem 0.5rem;
        }

        /* Pagination styling */
        .pagination {
            margin-bottom: 0;
        }

        /* Form focus styling */
        .form-select:focus,
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        /* Badge styling */
        .badge {
            font-weight: 500;
            padding: 0.35rem 0.65rem;
            border-radius: 0.375rem;
        }

        .badge.bg-light {
            background-color: #f8f9fa !important;
            border: 1px solid #dee2e6;
        }

        /* Input group focus */
        .input-group:focus-within {
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            border-radius: 0.375rem;
        }

        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control {
            border-color: #667eea;
        }

        /* Table header styling */
        .table thead th {
            background-color: #f8f9fa;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .stat-card {
                padding: 1rem;
            }

            .table {
                font-size: 0.875rem;
            }

            .role-icon-sm {
                width: 32px;
                height: 32px;
                font-size: 1rem;
            }

            .btn-group .btn {
                padding: 0.2rem 0.4rem;
            }
        }
    </style>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Delete modal functionality
                const deleteButtons = document.querySelectorAll('.delete-role-btn');
                const deleteForm = document.getElementById('deleteForm');
                const roleNameSpan = document.getElementById('roleName');

                deleteButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const roleId = this.getAttribute('data-role-id');
                        const roleName = this.getAttribute('data-role-name');

                        deleteForm.action = `/admin/roles/${roleId}`;
                        roleNameSpan.textContent = roleName;
                    });
                });

                // Search functionality
                const searchInput = document.getElementById('searchInput');
                const permissionFilter = document.getElementById('permissionFilter');
                const tableRows = document.querySelectorAll('#rolesTable tbody tr');

                function filterTable() {
                    const searchTerm = searchInput.value.toLowerCase();
                    const permissionValue = permissionFilter.value.toLowerCase();

                    tableRows.forEach(row => {
                        if (row.cells && row.cells.length > 0) {
                            const roleNameCell = row.cells[1];
                            const roleName = roleNameCell?.textContent.toLowerCase() || '';
                            const rolePermissions = row.getAttribute('data-role-permissions')?.toLowerCase() ||
                                '';

                            const matchesSearch = roleName.includes(searchTerm);
                            const matchesPermission = !permissionValue || rolePermissions.includes(
                                permissionValue);

                            row.style.display = (matchesSearch && matchesPermission) ? '' : 'none';
                        }
                    });
                }

                // Debounced search for better performance
                let searchTimeout;
                if (searchInput) {
                    searchInput.addEventListener('keyup', function() {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(filterTable, 300);
                    });
                }

                if (permissionFilter) {
                    permissionFilter.addEventListener('change', filterTable);
                }

                // Tooltip initialization
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });

                // Add click handler to rows for better UX
                const rows = document.querySelectorAll('#rolesTable tbody tr');
                rows.forEach(row => {
                    row.addEventListener('click', function(e) {
                        // Don't trigger if clicking on buttons or links
                        if (e.target.closest('.btn-group') || e.target.closest('a')) {
                            return;
                        }

                        // Find the edit link and click it
                        const editLink = this.querySelector('.btn-outline-primary');
                        if (editLink && editLink.href) {
                            window.location.href = editLink.href;
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
