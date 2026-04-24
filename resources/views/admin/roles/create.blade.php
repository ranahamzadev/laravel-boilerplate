@extends('layouts.app')

@section('title', 'Create Role')
@push('styles')
    <style>
        .user-form-wrapper {
            background-color: #fff;
            padding: 40px;
            min-height: 100vh;
            width: 100%;
        }

        .form-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .permissions-card {
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .permissions-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
        }

        .permissions-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            padding: 15px;
        }

        .permissions-table td {
            padding: 12px 15px;
            vertical-align: middle;
        }

        .badge-permission {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .form-check-label {
            margin-left: 8px;
            cursor: pointer;
        }

        .select-all-btn {
            padding: 4px 12px;
            font-size: 12px;
            border-radius: 6px;
        }

        .module-name {
            font-weight: 600;
            font-size: 16px;
            color: #2c3e50;
        }

        .permission-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .table-row-dashed>thead>tr>th,
        .table-row-dashed>tbody>tr>td {
            border-bottom: 1px dashed #e2e8f0;
        }
    </style>
@endpush

@section('content')
    <div class="user-form-wrapper">
        <div class="form-container">
            <form method="POST" action="{{ route('admin.roles.store') }}" enctype="multipart/form-data" class="form">
                @csrf

                <div class="mb-4">
                    <label class="form-label fw-bold fs-5 mb-2">
                        <span class="text-danger">*</span> Role Name
                    </label>
                    <input class="form-control form-control-lg @error('name') is-invalid @enderror"
                        placeholder="Enter a role name (e.g., admin, editor, manager)" name="name"
                        value="{{ old('name') }}" />
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <div class="form-text text-muted">Use lowercase letters and hyphens only</div>
                </div>

                <div class="card permissions-card">
                    <div class="permissions-header">
                        <h5 class="mb-0">
                            <i class="bi bi-shield-lock-check me-2"></i>
                            Role Permissions
                        </h5>
                        <small class="text-white-50">Select the permissions for this role</small>
                    </div>

                    @php
                        // Group permissions by module (the part AFTER the hyphen)
                        $groupedPermissions = collect();

                        foreach ($permissions as $permission) {
                            $name = $permission->name;
                            $parts = explode('-', $name);

                            if (count($parts) >= 2) {
                                $action = $parts[0];
                                $module = $parts[1];

                                if (count($parts) > 2) {
                                    $module = $parts[1] . '-' . $parts[2];
                                }

                                $group = ucfirst($module);
                            } else {
                                $group = ucfirst($name);
                                $action = $name;
                            }

                            if (!$groupedPermissions->has($group)) {
                                $groupedPermissions[$group] = collect();
                            }

                            $groupedPermissions[$group]->push($permission);
                        }

                        $groupedPermissions = $groupedPermissions->sortKeys();
                        $availableActions = ['create', 'view', 'edit', 'delete'];
                    @endphp

                    <div class="table-responsive">
                        <table class="table table-row-dashed align-middle permissions-table mb-0">

                            <tbody>
                                <tr class="bg-light-opacity">
                                    <td colspan="100%" class="text-center">
                                        <label class="d-flex align-items-center justify-content-center gap-2 mb-0">
                                            <input type="checkbox" class="form-check-input" id="all_permissions_global">
                                            <strong>
                                                <i class="bi bi-check-all me-1"></i>
                                                ALL PERMISSIONS (Select/Deselect All Modules)
                                            </strong>
                                        </label>
                                    </td>
                                </tr>

                                @foreach ($groupedPermissions as $group => $permissionsGroup)
                                    @php
                                        $permissionMap = [];
                                        foreach ($permissionsGroup as $perm) {
                                            $parts = explode('-', $perm->name);
                                            $action = $parts[0];
                                            $permissionMap[$action] = $perm;
                                        }

                                        $colors = [
                                            'create' => 'success',
                                            'view' => 'info',
                                            'edit' => 'primary',
                                            'delete' => 'danger',
                                        ];
                                    @endphp

                                    <tr>
                                        <td class="align-middle">
                                            <div class="module-name">
                                                {{ $group }}
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="permission-badge">
                                                <input type="checkbox" class="form-check-input group-checkbox"
                                                    data-group="{{ $group }}" id="group_{{ $group }}">
                                                <label class="form-check-label text-nowrap" for="group_{{ $group }}">
                                                    Select All
                                                </label>
                                            </div>
                                        </td>

                                        @foreach ($availableActions as $action)
                                            @php
                                                $permission = $permissionMap[$action] ?? null;
                                            @endphp

                                            <td class="text-center align-middle">
                                                @if ($permission)
                                                    <div class="permission-badge justify-content-center">
                                                        <input type="checkbox" class="form-check-input permission-checkbox"
                                                            name="permissions[]" value="{{ $permission->id }}"
                                                            data-group="{{ $group }}"
                                                            data-action="{{ $action }}"
                                                            id="perm_{{ $permission->id }}"
                                                            {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                            <span
                                                                class="text-{{ $colors[$action] ?? 'secondary' }} badge-permission">
                                                                {{ ucfirst($action) }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach

                                @if ($groupedPermissions->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="bi bi-exclamation-circle me-2"></i>
                                            No permissions found in the system
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    @error('permissions')
                        <div class="text-danger mt-2 p-3">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-center pt-4 mt-3">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary btn-lg px-4 me-3">
                        <i class="bi bi-arrow-left me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="bi bi-save me-2"></i>Create Role
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Global Select All (All Modules)
            const globalCheckbox = document.getElementById('all_permissions_global');

            if (globalCheckbox) {
                globalCheckbox.addEventListener('change', function() {
                    const isChecked = this.checked;

                    // Select/Deselect all permission checkboxes
                    const allPermissionCheckboxes = document.querySelectorAll('.permission-checkbox');
                    allPermissionCheckboxes.forEach(checkbox => {
                        checkbox.checked = isChecked;
                    });

                    // Update all group checkboxes
                    const allGroupCheckboxes = document.querySelectorAll('.group-checkbox');
                    allGroupCheckboxes.forEach(groupCheckbox => {
                        const group = groupCheckbox.getAttribute('data-group');
                        updateGroupSelectAllState(group);
                    });
                });
            }

            // Module Group Select All
            const groupCheckboxes = document.querySelectorAll('.group-checkbox');
            groupCheckboxes.forEach(groupCheckbox => {
                groupCheckbox.addEventListener('change', function() {
                    const group = this.getAttribute('data-group');
                    const isChecked = this.checked;

                    // Select/Deselect all permissions in this group
                    const groupPermissions = document.querySelectorAll(
                        `.permission-checkbox[data-group="${group}"]`);
                    groupPermissions.forEach(checkbox => {
                        checkbox.checked = isChecked;
                    });

                    // Update global select all state
                    updateGlobalSelectAllState();
                });
            });

            // Individual Permission Checkbox
            const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
            permissionCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const group = this.getAttribute('data-group');

                    // Update the group checkbox state
                    updateGroupSelectAllState(group);

                    // Update global select all state
                    updateGlobalSelectAllState();
                });
            });

            // Update group checkbox state (checked, unchecked, indeterminate)
            function updateGroupSelectAllState(group) {
                const groupCheckbox = document.querySelector(`.group-checkbox[data-group="${group}"]`);
                const checkboxes = document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`);
                const checkedCount = document.querySelectorAll(
                    `.permission-checkbox[data-group="${group}"]:checked`).length;
                const totalCount = checkboxes.length;

                if (!groupCheckbox) return;

                if (checkedCount === 0) {
                    groupCheckbox.checked = false;
                    groupCheckbox.indeterminate = false;
                } else if (checkedCount === totalCount) {
                    groupCheckbox.checked = true;
                    groupCheckbox.indeterminate = false;
                } else {
                    groupCheckbox.checked = false;
                    groupCheckbox.indeterminate = true;
                }
            }

            // Update global select all checkbox state
            function updateGlobalSelectAllState() {
                const globalCheckbox = document.getElementById('all_permissions_global');
                if (!globalCheckbox) return;

                const totalCheckboxes = document.querySelectorAll('.permission-checkbox').length;
                const checkedCheckboxes = document.querySelectorAll('.permission-checkbox:checked').length;

                if (totalCheckboxes === 0) {
                    globalCheckbox.checked = false;
                    globalCheckbox.indeterminate = false;
                } else if (checkedCheckboxes === totalCheckboxes) {
                    globalCheckbox.checked = true;
                    globalCheckbox.indeterminate = false;
                } else if (checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes) {
                    globalCheckbox.checked = false;
                    globalCheckbox.indeterminate = true;
                } else {
                    globalCheckbox.checked = false;
                    globalCheckbox.indeterminate = false;
                }
            }

            // Initialize all states
            function initializeStates() {
                const allGroups = document.querySelectorAll('.group-checkbox');
                allGroups.forEach(group => {
                    const groupName = group.getAttribute('data-group');
                    updateGroupSelectAllState(groupName);
                });
                updateGlobalSelectAllState();
            }

            // Call initialization
            initializeStates();
        });
    </script>
@endpush
