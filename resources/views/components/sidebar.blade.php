@php use App\Services\AllServices;use Illuminate\Support\Facades\Auth; @endphp
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route("dashboard") }}" class="brand-link">
        <img src="{{ asset("src/img/logo.png") }}" alt="Logo" class="brand-image elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"><strong>SPM</strong> IT Del</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- SidebarSearch Form -->
        <div class="form-inline mt-3 mb-3">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                <li class="nav-item">
                    <a href="{{ route('indexlogindashboard') }}"
                       class="nav-link {{ $active_sidebar[0] == 1 ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Admin Dashboard
                        </p>
                    </a>
                </li>

                @if(Auth::check() && AllServices::isCurrentRole("admin"))
                    <li class="nav-item">
                        <a href="{{ route('news') }}" class="nav-link {{ $active_sidebar[0] == 2 ? 'active' : '' }}">
                            <i class="nav-icon fas fa-newspaper"></i>
                            <p>
                                News
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('dashboard-admin') }}"
                           class="nav-link {{ $active_sidebar[0] == 3 ? 'active' : '' }}">
                            <i class="nav-icon fas fa-window-restore"></i>
                            <p>
                                Dashboard Management
                            </p>
                        </a>
                    </li>
                @endif

                @if(Auth::check() && auth()->user()->role != null)
                    <li class="nav-item {{ $active_sidebar[0] == 3 ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $active_sidebar[0] == 4 ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>
                                Pengaturan Pengguna
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('user-settings-active') }}"
                                   class="nav-link {{ $active_sidebar[1] == 1 ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pengguna Aktif</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('user-settings-inactive') }}"
                                   class="nav-link {{ $active_sidebar[1] == 2 ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pengguna Tidak Aktif</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if(Auth::check() && auth()->user()->role != null)
                    <li class="nav-item {{ $active_sidebar[0] == 3 ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $active_sidebar[0] == 4 ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>
                                Laporan Dokumen
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('documentManagementAdd') }}"
                                   class="nav-link {{ $active_sidebar[1] == 1 ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Add</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('documentManagementReject') }}"
                                   class="nav-link {{ $active_sidebar[1] == 2 ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Reject</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if(auth()->user()->role != null)
                    <li class="nav-item">
                        <a href="{{ route('documentManagement') }}"
                           class="nav-link {{ $active_sidebar[0] == 5 ? 'active' : '' }}">
                            <i class="fas fa-file nav-icon"></i>
                            <p>
                                Document Management
                            </p>
                        </a>
                    </li>
                @endif

                @if(AllServices::isCurrentRole('admin'))
                    <li class="nav-item">
                        <a href="{{ route('role-management') }}"
                           class="nav-link {{ $active_sidebar[0] == 6 ? 'active' : '' }}">
                            <i class="nav-icon fas fa-crown"></i>
                            <p>
                                Manajemen Peran
                            </p>
                        </a>
                    </li>
                @endif

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
