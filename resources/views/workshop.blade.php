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
    </div> 
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>



</body>
</html>



    <div style="text-align:center; font-size: 30px" class="container">
        <b>WorkShop Title: {{$workshop->title}}
        <br/>
         WorkShop Key: {{$workshop->key}}
         @if(isset($error))
            <br>
            <div class="row">
                <div class="col-md-6   col-md-offset-3">
                    <div class="alert alert-danger " style="text-align: center; font-size: 30px;">
                        <h5>{{$error}}</h5>
                    </div>
                </div>
            </div>
        @endif
         </b>
    </div>
        <div style="text-align:center; padding-top: 5%" class="row">
            <div class="col-md-6"><h4>{{$workshop->body}}</h4></div>
            <div class="col-md-6">
                <div style="margin-left: 20%;margin-right: 35%" class ="well">Number Of Participants: {{$workshop->nb_of_participated}}   &nbsp &nbsp &nbsp &nbsp  Out of: {{$workshop->nb_of_users}}</div>
            </div>
        </div>
        <div style="text-align: center;">
            <form method="POST" action="{{URL::to('/workshop/Moniterstart')}}">
                {{ csrf_field() }}
                {{method_field('PUT')}}
                <div class="container">
                    <div class="row">
                        <textarea name='card' style=" resize: none; width: 996px; height: 313px;" required></textarea>
                    </div>
                    <div>
                        <button class="btn btn-primary" type="submit">Start Workshop</button>
                    </div>
                </div>
                <input type="hidden" name="key" value ='{{$workshop->key}}'/>
            </form>
        <div>
    </div>
