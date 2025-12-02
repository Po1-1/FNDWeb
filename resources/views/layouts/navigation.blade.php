<nav class="navbar navbar-expand-lg sticky-top mb-4">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <i class="bi bi-grid-fill me-2 text-primary"></i>
            {{ config('app.name', 'FNDWeb') }}
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#appNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="appNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}" href="{{ route('dashboard') }}">
                        Dashboard
                    </a>
                </li>
                
                @if (Auth::user()->role == 'admin')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Data Master</a>
                        <ul class="dropdown-menu border-0 shadow-sm rounded-3">
                            <li><a class="dropdown-item" href="{{ route('admin.mahasiswa.index') }}">Mahasiswa</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.kelompok.index') }}">Kelompok</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.vendors.index') }}">Vendor</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.makanan.index') }}">Makanan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">Users</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.events.index') }}">Events</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.logistik.index') }}">Logistik</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.summaries.index') }}">Laporan</a></li>
                @endif
            </ul>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2 text-primary fw-bold" style="width: 35px; height: 35px;">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">Log Out</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>