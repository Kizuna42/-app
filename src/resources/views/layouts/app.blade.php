<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>フリマアプリ</title>
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <style>
        @media (min-width: 768px) and (max-width: 850px) {
            .navbar .search-form {
                width: 200px;
                margin: 0 0.5rem;
            }
            .navbar .search-input {
                width: 100%;
                font-size: 0.9rem;
                padding: 6px 12px;
            }
            .navbar-brand img {
                height: 26px;
            }
            .nav-link {
                padding: 0.5rem 0.4rem;
                font-size: 0.85rem;
                white-space: nowrap;
            }
            .navbar .btn-light {
                padding: 4px 10px;
                font-size: 0.85rem;
            }
            .container {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
        }

        @media (min-width: 1400px) and (max-width: 1540px) {
            .navbar .search-form {
                width: 450px;
            }
        }

        .navbar .search-form {
            position: relative;
            margin: 0 2rem;
        }

        .navbar .search-input {
            border: none;
            padding: 8px 16px;
            width: 100%;
        }

        .navbar .nav-item {
            display: flex;
            align-items: center;
        }

        .navbar .btn-light {
            padding: 6px 20px;
            font-weight: 500;
        }

        @media (max-width: 767px) {
            .navbar .search-form {
                margin: 1rem 0;
                width: 100%;
            }
            .navbar-nav {
                margin-top: 1rem;
            }
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('images/Free Market App Logo.svg') }}" alt="Logo" height="32">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarContent">
                    @if (!request()->is('login', 'register'))
                        <form class="search-form mx-auto" method="GET" action="{{ route('items.index') }}">
                            <input type="search"
                                name="search"
                                class="search-input"
                                placeholder="なにをお探しですか？"
                                aria-label="Search"
                                value="{{ request('search') }}">
                            @if(request('tab'))
                                <input type="hidden" name="tab" value="{{ request('tab') }}">
                            @endif
                        </form>

                        <ul class="navbar-nav align-items-center">
                            @auth
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('users.show') }}">マイページ</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                        ログアウト
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                                <li class="nav-item ms-2">
                                    <a class="btn btn-light" href="{{ route('items.create') }}">出品</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('login') }}">ログイン</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('register') }}">新規登録</a>
                                </li>
                            @endauth
                        </ul>
                    @endif
                </div>
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // CSRFトークンをAjaxリクエストのデフォルトヘッダーに設定
        document.addEventListener('DOMContentLoaded', function() {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            window.axios = {
                defaults: {
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                }
            };
        });
    </script>
    @stack('scripts')
</body>
</html>