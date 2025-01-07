<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('images/Free Market App Logo.svg') }}" alt="Logo" height="32">
                </a>
                @if (!request()->is('login') && !request()->is('register'))
                    <form form class="d-flex mx-auto" method="GET" action="{{ route('items.index') }}">
                        <input class="" type="search" name="search" placeholder="なにをお探しですか？" aria-label="Search" value="{{ request('search') }}" style="padding: 8px; width: 450px;">
                    </form>
                @endif
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                ログアウト
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users.show') }}">マイページ</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-light" href="{{ route('items.create') }}">出品</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </nav>

        <main class="py-4">
            @if (session('success'))
                <div class="container">
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="container">
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>