<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">Poliklinik</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random"
                    class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                {{-- Pastikan pakai 'name' sesuai database --}}
                <a href="#" class="d-block">Halo, {{ Auth::user()->name }}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                @if (Auth::user()->role == 'admin')
                    <li class="nav-header">ADMINISTRATOR</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dokters.index') }}" class="nav-link {{ request()->routeIs('dokters.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-md"></i>
                            <p>Manajemen Dokter</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('polis.index') }}" class="nav-link {{ request()->routeIs('polis.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-hospital"></i>
                            <p>Manajemen Poli</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('pasien.index') }}" class="nav-link {{ request()->routeIs('pasien.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-injured"></i>
                            <p>Manajemen Pasien</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('obat.index') }}" class="nav-link {{ request()->routeIs('obat.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-pills"></i>
                            <p>Manajemen Obat</p>
                        </a>
                    </li>
                @endif


                @if (Auth::user()->role == 'dokter')
                    <li class="nav-header">DOKTER</li>
                    <li class="nav-item">
                        <a href="{{ route('dokter.dashboard') }}" class="nav-link {{ request()->routeIs('dokter.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-columns"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('jadwal-periksa.index') }}" class="nav-link {{ request()->routeIs('jadwal-periksa.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-check"></i>
                            <p>Jadwal Periksa</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('periksa.pasien.index') }}" class="nav-link {{ request()->routeIs('periksa.pasien.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-stethoscope"></i>
                            <p>Periksa Pasien</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dokter.riwayat-pasien.index') }}" class="nav-link {{ request()->routeIs('dokter.riwayat-pasien.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history"></i>
                            <p>Riwayat Pasien</p>
                        </a>
                    </li>
                @endif


                @if (Auth::user()->role == 'pasien')
                    <li class="nav-header">PASIEN</li>
                    <li class="nav-item">
                        <a href="{{ route('pasien.dashboard') }}" class="nav-link {{ request()->routeIs('pasien.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-columns"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('pasien.daftar') }}" class="nav-link {{ request()->routeIs('pasien.daftar') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-hospital-user"></i>
                            <p>Daftar Poli</p>
                        </a>
                    </li>
                @endif

                <li class="nav-item mt-3">
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="nav-link btn btn-danger text-left w-100">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Keluar</p>
                        </button>
                    </form>
                </li>

            </ul>
        </nav>
    </div>
</aside>