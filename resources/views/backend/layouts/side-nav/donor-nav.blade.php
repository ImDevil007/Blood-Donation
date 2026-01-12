<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('backend.donor.dashboard') }}" class="brand-link">
        <img src="/images/logo.png" alt="Vital Blood Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">Vital Blood</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="/images/avatar.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->full_name }}</a>
            </div>
        </div>

        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                {{-- Dashboard --}}
                <li class="nav-item">
                    <a href="{{ route('backend.donor.dashboard') }}"
                        class="nav-link {{ request()->routeIs('backend.donor.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- Donation History --}}
                <li class="nav-item">
                    <a href="{{ route('backend.donor.history') }}"
                        class="nav-link {{ request()->routeIs('backend.donor.history') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Donation History</p>
                    </a>
                </li>

                {{-- Sign Out --}}
                <li class="nav-item">
                    <a href="#" class="nav-link"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Sign Out</p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>
