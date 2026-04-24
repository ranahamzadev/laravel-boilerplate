<!-- Header Styles -->
<style>
    .top-header {
        position: sticky;
        top: 0;
        background: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        padding: 0.75rem 1.5rem;
        height: var(--header-height);
        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 1020;
        margin-left: var(--sidebar-width);
        width: calc(100% - var(--sidebar-width));
        transition: all 0.3s ease;
    }

    .main-content.expanded+.top-header,
    body:has(.main-content.expanded) .top-header {
        margin-left: var(--sidebar-collapsed-width);
        width: calc(100% - var(--sidebar-collapsed-width));
    }

    .toggle-btn {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 8px;
        transition: all 0.3s;
        color: #333;
    }

    .toggle-btn:hover {
        background: #f0f0f0;
    }

    /* User Dropdown */
    .user-dropdown .dropdown-toggle {
        background: none;
        border: none;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0.5rem 1rem;
        border-radius: 8px;
    }

    .user-dropdown .dropdown-toggle:hover {
        background: #f8f9fa;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .top-header {
            margin-left: 0;
            width: 100%;
        }
    }
</style>

<!-- Header -->
<div class="top-header">
    <div>
        <button class="toggle-btn" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>
        <button class="toggle-btn d-md-none ms-2" id="mobileSidebarToggle">
            <i class="bi bi-justify"></i>
        </button>
    </div>

    <div class="user-dropdown dropdown">
        <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <div class="user-avatar">
                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
            </div>
            <span class="d-none d-md-block">{{ Auth::user()->name ?? 'User' }}</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                    <i class="bi bi-person me-2"></i>My Profile
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="#">
                    <i class="bi bi-gear me-2"></i>Settings
                </a>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>