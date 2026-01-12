<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('backend.admin.dashboard') }}" class="brand-link">
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
                    <a href="{{ route('backend.admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('backend.admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- Donors --}}
                <li class="nav-item {{ request()->routeIs('backend.admin.donors.*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('backend.admin.donors.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-heart"></i>
                        <p>
                            Donors
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.donors.create') }}"
                                class="nav-link {{ request()->routeIs('backend.admin.donors.create') ? 'active' : '' }}">
                                <i class="far fa-plus-square nav-icon"></i>
                                <p>Register Donor</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.donors.index') }}"
                                class="nav-link {{ request()->routeIs('backend.admin.donors.index') ? 'active' : '' }}">
                                <i class="far fa-list-alt nav-icon"></i>
                                <p>Manage Donors</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Blood Collection Camps --}}
                <li
                    class="nav-item {{ request()->routeIs('backend.admin.blood-collection-camps.*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('backend.admin.blood-collection-camps.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-hospital"></i>
                        <p>
                            Collection Camps
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.blood-collection-camps.create') }}"
                                class="nav-link {{ request()->routeIs('backend.admin.blood-collection-camps.create') ? 'active' : '' }}">
                                <i class="far fa-plus-square nav-icon"></i>
                                <p>Schedule Camp</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.blood-collection-camps.index') }}"
                                class="nav-link {{ request()->routeIs('backend.admin.blood-collection-camps.index') ? 'active' : '' }}">
                                <i class="far fa-list-alt nav-icon"></i>
                                <p>Manage Camps</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Blood Testing --}}
                <li class="nav-item {{ request()->routeIs('backend.admin.blood-tests.*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('backend.admin.blood-tests.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-flask"></i>
                        <p>
                            Blood Testing
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.blood-tests.create') }}"
                                class="nav-link {{ request()->routeIs('backend.admin.blood-tests.create') ? 'active' : '' }}">
                                <i class="far fa-plus-square nav-icon"></i>
                                <p>Add Test</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.blood-tests.index') }}"
                                class="nav-link {{ request()->routeIs('backend.admin.blood-tests.index') ? 'active' : '' }}">
                                <i class="far fa-list-alt nav-icon"></i>
                                <p>Manage Tests</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Recipients --}}
                <li class="nav-item {{ request()->routeIs('backend.admin.recipients.*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('backend.admin.recipients.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-injured"></i>
                        <p>
                            Recipients
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.recipients.create') }}"
                                class="nav-link {{ request()->routeIs('backend.admin.recipients.create') ? 'active' : '' }}">
                                <i class="far fa-plus-square nav-icon"></i>
                                <p>Create Recipient</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.recipients.index') }}"
                                class="nav-link {{ request()->routeIs('backend.admin.recipients.index') ? 'active' : '' }}">
                                <i class="far fa-list-alt nav-icon"></i>
                                <p>Manage Recipients</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Blood Inventory --}}
                <li class="nav-item {{ request()->routeIs('backend.admin.blood-inventory.*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('backend.admin.blood-inventory.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tint"></i>
                        <p>
                            Blood Inventory
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.blood-inventory.create') }}"
                                class="nav-link {{ request()->routeIs('backend.admin.blood-inventory.create') ? 'active' : '' }}">
                                <i class="far fa-plus-square nav-icon"></i>
                                <p>Add Blood Stock</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.blood-inventory.index') }}"
                                class="nav-link {{ request()->routeIs('backend.admin.blood-inventory.index') ? 'active' : '' }}">
                                <i class="far fa-list-alt nav-icon"></i>
                                <p>Manage Inventory</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Blood Transfers --}}
                <li class="nav-item {{ request()->routeIs('backend.admin.blood-transfers.*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('backend.admin.blood-transfers.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-exchange-alt"></i>
                        <p>
                            Blood Transfers
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.blood-transfers.create') }}"
                                class="nav-link {{ request()->routeIs('backend.admin.blood-transfers.create') ? 'active' : '' }}">
                                <i class="far fa-plus-square nav-icon"></i>
                                <p>Create Transfer</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.blood-transfers.index') }}"
                                class="nav-link {{ request()->routeIs('backend.admin.blood-transfers.index') ? 'active' : '' }}">
                                <i class="far fa-list-alt nav-icon"></i>
                                <p>Manage Transfers</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Blood Units --}}
                <li class="nav-item {{ request()->routeIs('backend.admin.blood-units.*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('backend.admin.blood-units.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-vial"></i>
                        <p>
                            Blood Units
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.blood-units.create') }}"
                                class="nav-link {{ request()->routeIs('backend.admin.blood-units.create') ? 'active' : '' }}">
                                <i class="far fa-plus-square nav-icon"></i>
                                <p>Add Blood Unit</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.blood-units.index') }}"
                                class="nav-link {{ request()->routeIs('backend.admin.blood-units.index') ? 'active' : '' }}">
                                <i class="far fa-list-alt nav-icon"></i>
                                <p>Manage Units</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Analytics & Reports --}}
                <li class="nav-item">
                    <a href="{{ route('backend.admin.reports') }}"
                        class="nav-link {{ request()->routeIs('backend.admin.reports') ? 'active' : '' }}">
                        <i class="far fa-file-alt nav-icon"></i>
                        <p>Generate Reports</p>
                    </a>
                </li>

                {{-- AI Features --}}
                <li class="nav-item {{ request()->routeIs('backend.admin.ai.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('backend.admin.ai.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-robot"></i>
                        <p>
                            AI Features
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.ai.compatibility') }}"
                                class="nav-link {{ request()->routeIs('backend.admin.ai.compatibility*') ? 'active' : '' }}">
                                <i class="far fa-heart nav-icon"></i>
                                <p>Blood Compatibility</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.ai.eligibility') }}"
                                class="nav-link {{ request()->routeIs('backend.admin.ai.eligibility*') ? 'active' : '' }}">
                                <i class="fas fa-user-check nav-icon"></i>
                                <p>Donor Eligibility</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Users --}}
                <li class="nav-item {{ request()->routeIs('backend.admin.users.*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('backend.admin.users.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Users
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.users.create') }}"
                                class="nav-link {{ request()->routeIs('backend.admin.users.create') ? 'active' : '' }}">
                                <i class="far fa-plus-square nav-icon"></i>
                                <p>Create User</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.users.index') }}"
                                class="nav-link {{ request()->routeIs('backend.admin.users.index') ? 'active' : '' }}">
                                <i class="far fa-list-alt nav-icon"></i>
                                <p>Manage Users</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Roles --}}
                <li class="nav-item {{ request()->routeIs('backend.admin.roles.*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('backend.admin.roles.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p>
                            Roles
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.roles.create') }}"
                                class="nav-link {{ request()->routeIs('backend.admin.roles.create') ? 'active' : '' }}">
                                <i class="far fa-plus-square nav-icon"></i>
                                <p>Create Role</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.roles.index') }}"
                                class="nav-link {{ request()->routeIs('backend.admin.roles.index') ? 'active' : '' }}">
                                <i class="far fa-list-alt nav-icon"></i>
                                <p>Manage Roles</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Permissions --}}
                <li class="nav-item {{ request()->routeIs('backend.admin.permissions.*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('backend.admin.permissions.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-lock"></i>
                        <p>
                            Permissions
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.permissions.create') }}"
                                class="nav-link {{ request()->routeIs('backend.admin.permissions.create') ? 'active' : '' }}">
                                <i class="far fa-plus-square nav-icon"></i>
                                <p>Create Permission</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.permissions.index') }}"
                                class="nav-link {{ request()->routeIs('backend.admin.permissions.index') ? 'active' : '' }}">
                                <i class="far fa-list-alt nav-icon"></i>
                                <p>Manage Permissions</p>
                            </a>
                        </li>
                    </ul>
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
