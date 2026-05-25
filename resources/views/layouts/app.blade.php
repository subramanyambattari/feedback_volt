<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Feedback of Power Supply Position' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen">

    <div class="mx-auto min-h-screen max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        <nav class="app-navbar">

            <a href="{{ route('home') }}" class="app-brand">
                <span class="app-brand-mark">PS</span>

                <span>
                    <span class="block text-sm font-extrabold leading-tight text-white">
                        Power Supply
                    </span>

                    <span class="block text-xs font-semibold text-cyan-100/70">
                        Feedback Portal
                    </span>
                </span>
            </a>

            <div class="app-nav-links">

                @guest
                    <a href="{{ route('home') }}"
                       class="nav-link {{ request()->routeIs('home') ? 'nav-link-active nav-link-glow' : '' }}">
                        Home
                    </a>

                    <a href="{{ route('login') }}"
                       class="nav-link {{ request()->routeIs('login') ? 'nav-link-active nav-link-glow' : '' }}">
                        Login
                    </a>

                    <a href="{{ route('register') }}"
                       class="nav-link {{ request()->routeIs('register') ? 'nav-link-active nav-link-glow' : '' }}">
                        Registration
                    </a>
                @endguest

                @auth

                    @if (auth()->user()->isAdmin())

                        <a href="{{ route('home') }}"
                           class="nav-link">
                            Home
                        </a>

                        <a href="{{ route('admin.index') }}"
                           class="nav-link">
                            Admin
                        </a>

                        <a href="{{ route('admin.technicians') }}"
                           class="nav-link">
                            Technicians
                        </a>

                    @else

                        <a href="{{ route('home') }}"
                           class="nav-link">
                            Home
                        </a>

                        <a href="{{ route('dashboard') }}"
                           class="nav-link">
                            Dashboard
                        </a>

                        <a href="{{ route('feedback.create') }}"
                           class="nav-link">
                            Feedback
                        </a>

                        <a href="{{ route('feedback.index') }}"
                           class="nav-link">
                            Responses
                        </a>

                        <a href="{{ route('complaints.tracking') }}"
                           class="nav-link">
                            Tracking
                        </a>

                        <a href="{{ route('emergency') }}"
                           class="nav-link">
                            Emergency
                        </a>

                    @endif

                    <a href="{{ route('assistant') }}"
                       class="nav-link">
                        AI Assistant
                    </a>

                    <a href="{{ route('notifications') }}"
                       class="nav-link">
                        Notifications
                    </a>

                    <a href="{{ route('profile') }}"
                       class="nav-link">
                        Profile
                    </a>

                    <form action="{{ route('logout') }}"
                          method="POST"
                          class="contents">

                        @csrf

                        <button type="submit"
                                class="nav-link nav-link-logout">
                            Logout
                        </button>

                    </form>

                @endauth

            </div>

        </nav>

        @yield('content')

    </div>

</body>
</html>
