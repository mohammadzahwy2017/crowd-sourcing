<?php session_start() ?>
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

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



<div class="container">
        <div>
            <div class="container">
                <div style="text-align: center" class="row">
                    <div style="font-size: 50px" class="col-md-12" ><b>Title: {{$data->title}}</b><br>
                    <b>Shuffle Stage Number: {{$data->shuffle_stage}}</b></div>
                </div>
            </div>
        </div>
        <div>
            <div class="container">
                <div style="text-align:center;" class="row">
                    <div style="font-size: 40px" class="col-md-12"><b>Question: {{$data->question}}</b></div>
                </div>
            </div>
        </div>
        <form method="POST" action="{{URL::to('/workshop/submit_grade')}}">
            {{ csrf_field() }}
            {{method_field('PUT')}}
            
            <div style="margin-top: 50px;text-align:center" class="container">
                <div>
                    <p style="font-size: 30px"> 
                 <b>Vote With Respect To Answeat: </b><u>{{$answer}}</u>
                    </p>
                </div>
                <div>
                  <br/>
                    <b style="font-size: 22px">Enter the Grade:&nbsp &nbsp &nbsp &nbsp </b>  <input type="number" name="grade" value=0 min="0" max="10" style="width: 6%;"/>
                    <br/>
                    <br/>
                  <button class="btn btn-primary" name="submit" type="submit" style="width:8%;">Vote</button>
                </div>
                <input type="hidden" name="idea_id" value="{{$idea_id}}"/>
                
                <input type="hidden" name='key' value="{{$data->key}}"/>
            </div>
        </form>
    </div>

