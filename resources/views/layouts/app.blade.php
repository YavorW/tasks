<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $page_title ?? config('app.name', 'Laravel') }}</title>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    @isset($livewire) @livewireStyles @endisset

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">

    <style>
    </style>
    @stack('styles')

</head>

<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('projects.index') }}">Проекти</a>
                    </li>
                    @can('admin')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.users.index') }}">Потребители</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.settings.index') }}">Настройки</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.logs.index') }}">Log Explorer</a>
                    </li>
                    @endcan
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest
                    @if (Route::has('login'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @endif

                    @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                    @endif
                    @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('profile') }}">
                                <i class="fa fa-user"></i>
                                Профил
                            </a>

                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                Изход
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <main class="py-4">
        <div class="container">
            <div class="d-flex justify-content-between">
                <div>
                    <h1 class="d-inline-block me-3">{{ $page_title ?? '' }}</h1>
                    {{-- добавяне на действия бутони --}}
                    @stack('actions')

                    @if(isset($actions))
                    @foreach($actions as $action)
                    <a class="btn {{ $action['type'] }} mr-2 mb-3" href="{{ $action['link'] }}">
                        @if( mb_strtolower($action['anchor']) == 'назад')
                        <i class="far fa-arrow-alt-circle-left"></i>
                        @endif
                        {{ $action['anchor'] }}
                    </a>
                    @endforeach

                    @if (isset($delete))
                    <form method="POST" action="{{ $delete }}" class="d-inline" onSubmit="return confirm('Потвърди?')">
                        @csrf()
                        @method('delete')
                        <button type="submit" class="btn btn-outline-danger mb-3" data-button-type="delete">
                            <i class="fa fa-trash"></i>
                            Изтрий
                        </button>
                    </form>
                    @endif
                    @endif
                </div>
                <div>

                    {{-- Breadcrumbs --}}
                    @if (isset($breadcrumbs) && is_array($breadcrumbs) && count($breadcrumbs))
                    <ol class="breadcrumb float-sm-right mt-2">
                        @foreach ($breadcrumbs as $label => $link)
                        @if ($link)
                        <li class="breadcrumb-item text-capitalize"><a href="{{ $link }}">{{ $label }}</a></li>
                        @else
                        <li class="breadcrumb-item text-capitalize active" aria-current="page">{{ $label }}</li>
                        @endif
                        @endforeach
                    </ol>
                    @endif

                </div>
            </div>

            @isset($filters)
            <div class="col-md-12 col-sm-12">
                {{ $filters ?? ''}}
            </div>
            @endisset
        </div>

        @yield('content')
        {{ isset($slot) ? $slot : '' }}
    </main>

    <footer class="mt-auto container mb-3">
        <div class="text-end text-muted">
            версия 0.3-алфа; поздрави от Явката {!! config('app.footer') !!}
        </div>
    </footer>

    @isset($livewire) @livewireScripts @endisset
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- Alpinejs -->
    <script src="{{ asset('js/alpine.min.js') }}"></script>

    @stack('scripts')

    <script>
        window.onload = function() {
            
            {{-- https://github.com/prologuephp/alerts --}}
            @foreach (\Alert::getMessages() as $type => $messages)
            @foreach ($messages as $message)
            toastr.{{ $type }}("{!! str_replace('"', "'", $message) !!}")
            @endforeach
            @endforeach
            
            @isset($livewire)
            window.livewire.on('alert', alert => {
                toastr[alert['type']](alert['message']);
            });
            @endisset
            
        }
    </script>

    @isset($after_scripts) {!! $after_scripts !!} @endisset
</body>

</body>

</html>