<nav class="navbar navbar-expand-lg sticky-top mb-4">
    <div class="container">
        {{-- UBAH DI SINI: Link logo mengarah ke 'home' (halaman awal), bukan 'dashboard' --}}
        <a class="navbar-brand fw-bold text-primary" href="{{ route('home') }}">
            FNDWeb
        </a>
        
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#appNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="appNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @if(Auth::user()->role === 'admin')
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.events.index') }}">Events</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Data Master</a>
                        <ul class="dropdown-menu border-0 shadow-lg rounded-4 mt-2 p-2">
                            <li><a class="dropdown-item rounded-3 px-3 py-2" href="{{ route('admin.mahasiswa.index') }}">Mahasiswa</a></li>
                            <li><a class="dropdown-item rounded-3 px-3 py-2" href="{{ route('admin.kelompok.index') }}">Kelompok</a></li>
                            <li><a class="dropdown-item rounded-3 px-3 py-2" href="{{ route('admin.vendors.index') }}">Vendor</a></li>
                            <li><a class="dropdown-item rounded-3 px-3 py-2" href="{{ route('admin.makanan.index') }}">Makanan</a></li>
                             <li><a class="dropdown-item rounded-3 px-3 py-2" href="{{ route('admin.alergi.index') }}">Alergi</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item rounded-3 px-3 py-2 text-muted" href="{{ route('admin.users.index') }}">Users</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.logistik.index') }}">Logistik</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.summaries.index') }}">Laporan</a></li>
                @endif
            </ul>

            <ul class="navbar-nav d-lg-none">
                <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                @if (Auth::user()->role == 'admin')
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.mahasiswa.index') }}">Mahasiswa</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.summaries.index') }}">Laporan</a></li>
                @endif

                @if(Auth::user()->role === 'developer')
                    <li class="nav-item"><a class="nav-link" href="{{ route('developer.tenants.index') }}">Manajemen Tenant</a></li>
                @endif
            </ul>

            <ul class="navbar-nav ms-auto">
                {{-- TAMPILKAN EVENT AKTIF DI SINI --}}
                @if (isset($activeEvent))
                    <li class="nav-item me-3 d-flex align-items-center">
                        <div class="d-flex align-items-center gap-2 text-nowrap bg-white px-3 py-2 rounded-pill border shadow-sm">
                            <i class="bi bi-calendar-event text-success"></i>
                            <div class="d-flex flex-column lh-1">
                                <span class="fw-bold small text-dark">{{ $activeEvent->nama_event }}</span>
                                <span class="text-muted" style="font-size: 0.7rem;">Event Aktif</span>
                            </div>
                        </div>
                    </li>
                @endif

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 p-1 pe-3 rounded-pill bg-white shadow-sm border" href="#" role="button" data-bs-toggle="dropdown">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px;">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span class="small fw-bold text-dark">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 mt-2 p-2">
                        <li><a class="dropdown-item rounded-3 px-3 py-2" href="{{ route('profile.edit') }}">Edit Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item rounded-3 px-3 py-2 text-danger fw-bold">
                                    Log Out
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>