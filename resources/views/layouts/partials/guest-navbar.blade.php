<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <i class="bi bi-box-seam-fill me-2 text-primary"></i>
            FND Distribusi
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#guestNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="guestNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-2">

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('guest.about') }}">Apa itu FND</a>
                </li>

                @guest
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-primary px-4 rounded-pill" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary px-4 rounded-pill" href="{{ route('register') }}">Register</a>
                    </li>
                @endguest

                @auth
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-primary px-4 rounded-pill" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
