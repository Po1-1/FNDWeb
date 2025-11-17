<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#appNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="appNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        Dashboard
                    </a>
                </li>
                
                @if (Auth::user()->role == 'admin')
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.mahasiswa.index') }}">Mahasiswa</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.vendors.index') }}">Vendors</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.makanan.index') }}">Makanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.kelompok.index') }}">Kelompok</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.events.index') }}">Event</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}">User</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.logistik.index') }}">Logistik</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.alergi.index') }}">Alergi</a></li>
                    @elseif (Auth::user()->role == 'mentor')
                    <li class="nav-item"><a class="nav-link" href="{{ route('mentor.kelompok.show') }}">Kelompok Saya</a></li>
                @endif
                </ul>

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button typeF="submit" class="dropdown-item">Log Out</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>