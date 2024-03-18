<!DOCTYPE html>
<html lang="en" data-theme="light">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Ticket | @yield('title')</title>
        <link
            href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css"
            rel="stylesheet"
            type="text/css"
        />
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
            integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"
        />
    </head>
    <body>
        <nav class="navbar bg-base-100 bg-slate-300">
            <div class="navbar-start">
                <div class="dropdown">
                    <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h8m-8 6h16"
                            />
                        </svg>
                    </div>
                    <ul
                        tabindex="0"
                        class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52"
                    >
                        <li><a href="{{ route('tickets.index') }}">Nyitott feladatok</a></li>
                        <li><a href="{{ route('tickets.closed') }}">Lezárt feladatok</a></li>
                        <li><a href="uj_feladat.html">Új feladat</a></li>
                        <li><a href="felhasznalok.html">Felhasználók (ADMIN)</a></li>
                        <li><a href="feladatok.html">Összes feladat (ADMIN)</a></li>
                    </ul>
                </div>
                <a class="btn btn-ghost text-xl">Ticket</a>
            </div>
            <div class="navbar-center hidden lg:flex">
                <ul class="menu menu-horizontal px-1">
                    <li><a href="{{ route('tickets.index') }}">Nyitott feladatok</a></li>
                    <li><a href="{{ route('tickets.closed') }}">Lezárt feladatok</a></li>
                    <li><a href="uj_feladat.html">Új feladat</a></li>
                    <li><a href="felhasznalok.html">Felhasználók (ADMIN)</a></li>
                    <li><a href="feladatok.html">Összes feladat (ADMIN)</a></li>
                </ul>
            </div>
            <div class="navbar-end">
                <ul class="menu menu-horizontal px-1">
                    <li><a>Bejelentkezés</a></li>
                    <li><a>Regisztráció</a></li>
                </ul>
            </div>
        </nav>
        <div class="container mx-auto px-5 my-3">
            @yield('content')
        </div>
        <script src="https://cdn.tailwindcss.com"></script>
    </body>
</html>
