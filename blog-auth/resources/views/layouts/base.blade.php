<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @if (View::hasSection('title'))
            @yield('title')
        @else
            Laravel Blog
        @endif
    </title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
    <!-- Bootstrap core CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <header class="mb-3">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/bootstrap.svg') }}" width="30" height="30" class="d-inline-block align-top" alt="Logo">
                Blog
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-nav" aria-controls="main-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="main-nav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ route('home') }}">Nyitólap</a>
                    </li>
                </ul>
                @guest
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active ml-auto">
                            <a class="nav-link" href="{{ route('login') }}">Bejelentkezés</a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ route('register') }}">Regisztráció</a>
                        </li>
                    </ul>
                @endguest
                @auth
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown mr-3 ml-auto">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <form action="{{ route('logout') }}" method="post">
                                    @csrf
                                    
                                    <a class="dropdown-item" href={{ route('logout') }} onclick="event.preventDefault(); this.closest('form').submit();">Kijelentkezés</a>
                                </form>
                            </div>
                        </li>
                    </ul>
                @endauth
            </div>
        </nav>
    </header>

    <main>
        @yield('main-content')
    </main>

    <hr>
    <footer>
        @yield('footer-content')
        <div class="container">
            <div class="d-flex flex-column align-items-center">
                <div>
                    <span class="small">Alapszintű Blog</span>
                    <span class="mx-1">·</span>
                    <span class="small">Laravel {{ app()->version() }}</span>
                    <span class="mx-1">·</span>
                    <span class="small">{{ phpversion() }}</span>
                </div>

                <div>
                    <span class="small">Szerveroldali webprogramozás 2020-21-2</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- JQuery -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>

</html>
