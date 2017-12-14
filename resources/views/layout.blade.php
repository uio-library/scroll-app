<!DOCTYPE html>
<html>
<head>
    <title>Scroll - Nettkurs fra UiO</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/app.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="shortcut icon" href="/images/favicon.ico">
    <link rel="apple-touch-icon-precomposed" href="/images/apple-touch-icon.png">

    @yield('head')
</head>
<body>

<nav class="navbar navbar-dark bg-dark navbar-expand-sm" style="margin-bottom: 2em;>

    <a class="navbar-brand" href="/">Scroll</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">

        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="/">Heim</a>
            </li>
            @can('show', 'App\User')
                <li class="nav-item">
                    <a class="nav-link" href="/users">Brukere</a>
                </li>
            @endcan
        </ul>

        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav my-2 my-sm-0">
            <!-- Authentication Links -->
            @if (Auth::guest())
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Logg inn</a>
                </li>
            @else
                <li class="nav-item dropdown">
                    <a href="#" id="navbarDropdown" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('account') }}">
                            Min konto
                        </a>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logg ut
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            @endif
        </ul>

    </div>
</nav>

@if (Session::has('status'))
    <div class="container">
        <div class="alert alert-success">
            {{ Session::get('status') }}
        </div>
    </div>
@endif

@if (Session::has('error'))
    <div class="container">
        <div class="alert alert-danger">
            {{ Session::get('error') }}
        </div>
    </div>
@endif

<div class="container-fluid">
    @yield('content')
</div>

<!--    <script src="{{ URL::to('js/vendor.js') }}"></script>
    <script src="{{ URL::to('js/app.js') }}"></script>
-->

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
@yield('script')
</body>
</html>
