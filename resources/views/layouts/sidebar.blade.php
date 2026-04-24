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
    .nav-dropdown .nav-link {
        position: relative;
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
        padding-left: 3.5rem;
        background: rgba(0, 0, 0, 0.2);
    }

    .sidebar.collapsed .sub-menu {
        display: none;
    }

    .sub-menu .nav-link {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
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
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="#" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Users</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="#" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                <i class="bi bi-shield-lock"></i>
                <span>Roles</span>
            </a>
        </li>

        <!-- Dropdown Menu -->
        <li class="nav-item nav-dropdown">
            <a href="#" class="nav-link {{ request()->routeIs('profile.edit') ? 'parent-active' : '' }}"
                data-bs-toggle="collapse" data-bs-target="#settingsMenu" role="button"
                aria-expanded="{{ request()->routeIs('profile.edit') ? 'true' : 'false' }}">
                <i class="bi bi-gear"></i>
                <span>Settings</span>
                <i class="bi bi-chevron-down dropdown-icon"></i>
            </a>
            <div class="collapse sub-menu {{ request()->routeIs('profile.edit') ? 'show' : '' }}" id="settingsMenu">
                <a href="{{ route('profile.edit') }}" class="nav-link">
                    <i class="bi bi-person"></i>
                    <span>Profile</span>
                </a>
                <a href="#" class="nav-link">
                    <i class="bi bi-sliders2"></i>
                    <span>Preferences</span>
                </a>
                <a href="#" class="nav-link">
                    <i class="bi bi-bell"></i>
                    <span>Notifications</span>
                </a>
            </div>
        </li>
    </ul>
</div>

<script>
    // Ensure dropdown stays open when child is active
    document.addEventListener('DOMContentLoaded', function () {
        // Check if any child of settings menu is active
        const settingsMenu = document.getElementById('settingsMenu');
        const settingsToggle = document.querySelector('[data-bs-target="#settingsMenu"]');

        if (settingsMenu && settingsMenu.querySelector('.nav-link.active')) {
            // Show the collapse menu
            settingsMenu.classList.add('show');
            // Set aria-expanded to true
            if (settingsToggle) {
                settingsToggle.setAttribute('aria-expanded', 'true');
            }
        }

        // Add click handler to maintain active state
        const subMenuLinks = document.querySelectorAll('.sub-menu .nav-link');
        subMenuLinks.forEach(link => {
            link.addEventListener('click', function () {
                // Remove active class from all submenu links
                subMenuLinks.forEach(l => l.classList.remove('active'));
                // Add active class to clicked link
                this.classList.add('active');

                // Keep the parent dropdown highlighted
                const parentDropdown = this.closest('.nav-dropdown');
                if (parentDropdown) {
                    const parentLink = parentDropdown.querySelector('> .nav-link');
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
    });
</script>