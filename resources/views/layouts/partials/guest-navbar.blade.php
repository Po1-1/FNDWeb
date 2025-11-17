<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            FND Distribusi
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#guestNavbar" aria-controls="guestNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="guestNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('guest.about') }}">Apa itu FND</a>
                </li>
                
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-primary btn-sm" href="{{ route('register') }}">Register</a>
                    </li>
                @endguest

                @auth
                     <li class="nav-item ms-lg-2">
                        <a class="btn btn-success btn-sm" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>