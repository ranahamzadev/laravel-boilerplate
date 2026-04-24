@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2><i class="bi bi-people me-2"></i>Users Management</h2>
                <p class="text-muted mb-0">Manage system users and their roles</p>
            </div>
            <div class="d-flex gap-2">
                @can('create users')
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="bi bi-person-plus me-2"></i>Add New User
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
                    <input type="text" id="searchInput" class="form-control"
                        placeholder="Search users by name or email...">
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-md-end gap-2">
                    <select id="roleFilter" class="form-select w-auto">
                        <option value="">All Roles</option>
                        @foreach (\Spatie\Permission\Models\Role::all() as $role)
                            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                    <select id="statusFilter" class="form-select w-auto">
                        <option value="">All Status</option>
                        <option value="verified">Verified</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Remove table-responsive and use overflow-x-auto -->
        <table class="table table-hover align-middle" id="usersTable" style="min-width: 700px;">
            <thead class="bg-light">
                <tr>
                    <th width="5%">ID</th>
                    <th width="20%">User</th>
                    <th width="20%">Email</th>
                    <th width="20%">Role</th>
                    <th width="10%">Status</th>
                    <th width="15%">Joined</th>
                    <th width="10%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr data-user-role="{{ $user->roles->pluck('name')->implode(',') }}"
                        data-user-status="{{ $user->email_verified_at ? 'verified' : 'pending' }}">
                        <td>{{ $user->id }}</td>
                        <td class="py-3">
                            <div class="d-flex align-items-center">
                                <div class="user-avatar-sm me-2 flex-shrink-0">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $user->name }}</div>
                                    <small class="text-muted">ID: #{{ $user->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            <div class="d-flex flex-column">
                                <span>{{ $user->email }}</span>
                                @if ($user->email_verified_at)
                                    <small class="text-success">
                                        <i class="bi bi-check-circle-fill me-1"></i>Verified
                                    </small>
                                @else
                                    <small class="text-warning">
                                        <i class="bi bi-clock-history me-1"></i>Not verified
                                    </small>
                                @endif
                            </div>
                        </td>
                        <td class="py-3">
                            <div class="d-flex flex-wrap gap-1">
                                @forelse($user->roles as $role)
                                    <span class="badge"
                                        style="background-color: #4a5568; color: white; padding: 0.35rem 0.65rem;">
                                        <i class="bi bi-shield-check me-1"></i>{{ $role->name }}
                                    </span>
                                @empty
                                    <span class="badge bg-secondary">No role</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="py-3">
                            @if ($user->email_verified_at)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>Active
                                </span>
                            @else
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-hourglass-split me-1"></i>Pending
                                </span>
                            @endif
                        </td>
                        <td class="py-3">
                            <small class="text-muted" title="{{ $user->created_at->format('F d, Y h:i A') }}">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ $user->created_at->diffForHumans() }}
                            </small>
                        </td>
                        <td class="py-3">
                            @can('edit users')
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary"
                                    title="Edit User">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @endcan
                            @can('delete users')
                                @if ($user->id !== auth()->id())
                                    <button type="button" class="btn btn-outline-danger delete-user-btn" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal" data-user-id="{{ $user->id }}"
                                        data-user-name="{{ $user->name }}" data-user-email="{{ $user->email }}"
                                        title="Delete User">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-people fs-1 text-muted"></i>
                            <p class="text-muted mt-2 mb-0">No users found</p>
                            @can('create users')
                                <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary mt-3">
                                    <i class="bi bi-person-plus me-2"></i>Add your first user
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
            Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
        </div>
        <div>
            {{ $users->links() }}
        </div>
    </div>
    </div>

    <!-- Enhanced Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header border-0">
                        <h5 class="modal-title text-danger" id="deleteModalLabel">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>Delete User
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body pt-0">
                        <div class="alert alert-danger">
                            <i class="bi bi-shield-exclamation me-2"></i>
                            <strong>Warning!</strong> This action cannot be undone.
                        </div>
                        <p>Are you sure you want to delete the user:</p>
                        <div class="bg-light p-3 rounded mb-3">
                            <div class="d-flex align-items-center">
                                <div class="user-avatar-sm me-3" id="deleteUserAvatar"></div>
                                <div>
                                    <strong id="deleteUserName" class="d-block"></strong>
                                    <small id="deleteUserEmail" class="text-muted"></small>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted small mb-0">All data associated with this user will be permanently deleted.
                        </p>
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
        .user-avatar-sm {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 1.1rem;
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

        /* Badge styling - dark and visible */
        .badge {
            font-weight: 500;
            padding: 0.35rem 0.65rem;
            border-radius: 0.375rem;
        }

        /* Role badge specific - dark background with white text */
        .role-badge {
            background-color: #2d3748 !important;
            color: #ffffff !important;
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

            .user-avatar-sm {
                width: 32px;
                height: 32px;
                font-size: 0.9rem;
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
                const deleteButtons = document.querySelectorAll('.delete-user-btn');
                const deleteForm = document.getElementById('deleteForm');
                const deleteUserName = document.getElementById('deleteUserName');
                const deleteUserEmail = document.getElementById('deleteUserEmail');
                const deleteUserAvatar = document.getElementById('deleteUserAvatar');

                deleteButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const userId = this.getAttribute('data-user-id');
                        const userName = this.getAttribute('data-user-name');
                        const userEmail = this.getAttribute('data-user-email');

                        deleteForm.action = `/admin/users/${userId}`;
                        deleteUserName.textContent = userName;
                        deleteUserEmail.textContent = userEmail;
                        deleteUserAvatar.textContent = userName.charAt(0).toUpperCase();
                    });
                });

                // Search functionality
                const searchInput = document.getElementById('searchInput');
                const roleFilter = document.getElementById('roleFilter');
                const statusFilter = document.getElementById('statusFilter');
                const tableRows = document.querySelectorAll('#usersTable tbody tr');

                function filterTable() {
                    const searchTerm = searchInput.value.toLowerCase();
                    const roleValue = roleFilter.value.toLowerCase();
                    const statusValue = statusFilter.value;

                    tableRows.forEach(row => {
                        if (row.cells && row.cells.length > 0) {
                            const nameCell = row.cells[1];
                            const emailCell = row.cells[2];
                            const name = nameCell?.textContent.toLowerCase() || '';
                            const email = emailCell?.textContent.toLowerCase() || '';
                            const userRole = row.getAttribute('data-user-role')?.toLowerCase() || '';
                            const userStatus = row.getAttribute('data-user-status') || '';

                            const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
                            const matchesRole = !roleValue || userRole.includes(roleValue);
                            const matchesStatus = !statusValue || userStatus === statusValue;

                            row.style.display = (matchesSearch && matchesRole && matchesStatus) ? '' : 'none';
                        }
                    });

                    // Update showing count
                    const visibleRows = Array.from(tableRows).filter(row => row.style.display !== 'none');
                    const showingInfo = document.querySelector('.text-muted.small');
                    if (showingInfo && !showingInfo.innerHTML.includes('Showing')) {
                        // Don't update if pagination is not simple search
                    }
                }

                // Debounced search for better performance
                let searchTimeout;
                searchInput.addEventListener('keyup', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(filterTable, 300);
                });

                roleFilter.addEventListener('change', filterTable);
                statusFilter.addEventListener('change', filterTable);

                // Tooltip initialization
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });
        </script>
    @endpush
@endsection
