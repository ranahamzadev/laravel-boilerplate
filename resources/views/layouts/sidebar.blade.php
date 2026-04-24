<!-- Sidebar Styles -->
<style>
    /* Sidebar Styles */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: var(--sidebar-width);
        height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transition: all 0.3s ease;
        z-index: 1030;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        overflow-y: auto;
        overflow-x: hidden;
    }

    .sidebar.collapsed {
        width: var(--sidebar-collapsed-width);
    }

    /* Custom scrollbar for sidebar */
    .sidebar::-webkit-scrollbar {
        width: 5px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 5px;
    }

    .sidebar-header {
        padding: 1.5rem;
        text-align: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar.collapsed .sidebar-header h3,
    .sidebar.collapsed .sidebar-header span {
        display: none;
    }

    .sidebar.collapsed .sidebar-header i {
        font-size: 1.8rem;
    }

    .sidebar-header h3 {
        color: white;
        font-size: 1.3rem;
        margin: 0;
    }

    .sidebar-header i {
        font-size: 2rem;
        color: white;
    }

    /* Navigation */
    .nav-menu {
        padding: 1rem 0;
        list-style: none;
        margin: 0;
    }

    .nav-item {
        list-style: none;
        margin-bottom: 0.5rem;
        width: 100%;
    }

    .nav-link {
        display: flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: all 0.3s;
        gap: 12px;
        cursor: pointer;
        white-space: nowrap;
        width: 100%;
        position: relative;
    }

    .nav-link:hover {
        color: white;
        background: rgba(255, 255, 255, 0.1);
    }

    .nav-link.active {
        color: white;
        background: rgba(255, 255, 255, 0.2);
        border-right: 3px solid white;
    }

    /* Parent active class for dropdown */
    .nav-link.parent-active {
        color: white;
        background: rgba(255, 255, 255, 0.15);
        border-right: 3px solid rgba(255, 255, 255, 0.5);
    }

    .nav-link i {
        font-size: 1.2rem;
        min-width: 24px;
    }

    .nav-link span {
        font-size: 0.9rem;
    }

    .sidebar.collapsed .nav-link span {
        display: none;
    }

    .sidebar.collapsed .nav-link {
        justify-content: center;
        padding: 0.75rem;
    }

    /* Dropdown in sidebar */
    .nav-dropdown {
        width: 100%;
    }

    .nav-dropdown .nav-link {
        position: relative;
        width: 100%;
    }

    .dropdown-icon {
        margin-left: auto;
        transition: transform 0.3s;
    }

    .nav-dropdown .nav-link[aria-expanded="true"] .dropdown-icon {
        transform: rotate(180deg);
    }

    .sidebar.collapsed .dropdown-icon {
        display: none;
    }

    .sub-menu {
        padding-left: 0;
        background: rgba(0, 0, 0, 0.2);
        width: 100%;
    }

    .sidebar.collapsed .sub-menu {
        display: none;
    }

    .sub-menu .nav-link {
        padding: 0.5rem 1rem 0.5rem 3.5rem;
        font-size: 0.85rem;
        width: 100%;
    }

    .sub-menu .nav-link i {
        font-size: 1rem;
        min-width: 20px;
    }

    /* Overlay for mobile */
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1025;
        display: none;
    }

    .overlay.show {
        display: block;
    }

    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.mobile-open {
            transform: translateX(0);
        }
    }
</style>

<!-- Sidebar Overlay for Mobile -->
<div class="overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <i class="bi bi-building"></i>
        <h3>{{ config('app.name', 'Laravel') }}</h3>
    </div>

    <ul class="nav-menu">
        @can('view-dashboard')
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        @endcan

        @canany(['view-roles', 'view-users'])
            @php
                $isAccessControlActive = request()->routeIs('admin.roles.*') || request()->routeIs('admin.users.*');
            @endphp
            <li class="nav-item nav-dropdown">
                <a href="#" class="nav-link {{ $isAccessControlActive ? 'parent-active' : '' }}"
                    data-bs-toggle="collapse" data-bs-target="#accessControlMenu" role="button"
                    aria-expanded="{{ $isAccessControlActive ? 'true' : 'false' }}">
                    <i class="bi bi-people"></i>
                    <span>User Management</span>
                    <i class="bi bi-chevron-down dropdown-icon"></i>
                </a>
                <div class="collapse sub-menu {{ $isAccessControlActive ? 'show' : '' }}" id="accessControlMenu">
                    @can('view-users')
                        <a href="{{ route('admin.users.index') }}"
                            class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="bi bi-people"></i>
                            <span>Users</span>
                        </a>
                    @endcan
                    @can('view-roles')
                        <a href="{{ route('admin.roles.index') }}"
                            class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                            <i class="bi bi-shield-lock"></i>
                            <span>Roles</span>
                        </a>
                    @endcan
                </div>
            </li>
        @endcanany

        <li class="nav-item">
            <a href="{{ route('profile.edit') }}"
                class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                <i class="bi bi-person"></i>
                <span>Profile Setting</span>
            </a>
        </li>
    </ul>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle dropdown active states
        const subMenuLinks = document.querySelectorAll('.sub-menu .nav-link');

        subMenuLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Don't prevent default, let the link work

                // Remove active class from all submenu links
                subMenuLinks.forEach(l => l.classList.remove('active'));

                // Add active class to clicked link
                this.classList.add('active');

                // Update parent dropdown highlight
                const parentDropdown = this.closest('.nav-dropdown');
                if (parentDropdown) {
                    const parentLink = parentDropdown.querySelector(':scope > .nav-link');
                    if (parentLink) {
                        // Remove parent-active from all dropdown parents
                        document.querySelectorAll('.nav-dropdown > .nav-link').forEach(l => {
                            l.classList.remove('parent-active');
                        });
                        parentLink.classList.add('parent-active');
                    }
                }
            });
        });

        // Check active routes on page load
        const currentPath = window.location.pathname;

        // If on users or roles page, highlight parent
        if (currentPath.includes('/admin/users') || currentPath.includes('/admin/roles')) {
            const parentDropdown = document.querySelector('.nav-dropdown');
            if (parentDropdown) {
                const parentLink = parentDropdown.querySelector(':scope > .nav-link');
                if (parentLink) {
                    parentLink.classList.add('parent-active');
                }
            }
        }
    });
</script>
