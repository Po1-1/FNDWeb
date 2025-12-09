<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - FND</title>

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>

<body class="bg-light">

    
    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ route('home') }}">FND Distribusi</a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#guestNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="guestNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
                    <li class="nav-item"><a class="nav-link" href="{{ route('guest.about') }}">Apa itu FND</a></li>

                    @guest
                        
                        <li class="nav-item"><a class="btn btn-outline-primary px-4" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item"><a class="btn btn-primary px-4" href="{{ route('register') }}">Register</a>
                        </li>
                    @endguest

                    @auth
                        
                        <li class="nav-item"><a class="btn btn-primary px-4" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger px-4">Logout</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-4">
        {{ $slot }}
    </main>

</body>

</html>
