<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Project Laravel</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                        <notification :unreads="{{auth()->user()->unreadNotifications}}" :userid="{{auth()->user()->id}}" ></notification>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>



<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
{{ csrf_field() }}
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="loader">
                <div class="loader-inner">
                    <div class="loading one"></div>
                </div>
                <div class="loader-inner">
                    <div class="loading two"></div>
                </div>
                <div class="loader-inner">
                    <div class="loading three"></div>
                </div>
                <div class="loader-inner">
                    <div class="loading four"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@if(isset($data))
            <h1 style="text-align: center;"><b>{{$data}}</b></h1>
    @endif
<style>
    .loader{
        width: 150px;
        height: 150px;
        margin: 40px auto;
        transform: rotate(-45deg);
        font-size: 0;
        line-height: 0;
        animation: rotate-loader 5s infinite;
        padding: 25px;
        border: 1px solid #cf303d;
    }
    .loader .loader-inner{
        position: relative;
        display: inline-block;
        width: 50%;
        height: 50%;
    }
    .loader .loading{
        position: absolute;
        background: #cf303d;
    }
    .loader .one{
        width: 100%;
        bottom: 0;
        height: 0;
        animation: loading-one 1s infinite;
    }
    .loader .two{
        width: 0;
        height: 100%;
        left: 0;
        animation: loading-two 1s infinite;
        animation-delay: 0.25s;
    }
    .loader .three{
        width: 0;
        height: 100%;
        right: 0;
        animation: loading-two 1s infinite;
        animation-delay: 0.75s;
    }
    .loader .four{
        width: 100%;
        top: 0;
        height: 0;
        animation: loading-one 1s infinite;
        animation-delay: 0.5s;
    }
    @keyframes loading-one {
        0% {
            height: 0;
            opacity: 1;
        }
        12.5% {
            height: 100%;
            opacity: 1;
        }
        50% {
            opacity: 1;
        }
        100% {
            height: 100%;
            opacity: 0;
        }
    }
    @keyframes loading-two {
        0% {
            width: 0;
            opacity: 1;
        }
        12.5% {
            width: 100%;
            opacity: 1;
        }
        50% {
            opacity: 1;
        }
        100% {
            width: 100%;
            opacity: 0;
        }
    }
    @keyframes rotate-loader {
        0% {
            transform: rotate(-45deg);
        }
        20% {
            transform: rotate(-45deg);
        }
        25% {
            transform: rotate(-135deg);
        }
        45% {
            transform: rotate(-135deg);
        }
        50% {
            transform: rotate(-225deg);
        }
        70% {
            transform: rotate(-225deg);
        }
        75% {
            transform: rotate(-315deg);
        }
        95% {
            transform: rotate(-315deg);
        }
        100% {
            transform: rotate(-405deg);
        }
    }
</style>