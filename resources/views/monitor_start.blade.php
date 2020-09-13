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


<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
<body>
    <div style="text-align:center;">
        <div>
            <div class="container" style="font-size: 30px">
                <div class="row">
                    <div class="col-md-12">
                        <b><u>WorkShop Title</u> : {{$workshop->title}}</b>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="container" style="font-size: 30px;">
                <div class="row">
                    <div>
                        <b><u>Workshop Description</u> :  {{$workshop->body}}</b>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="container"  style="font-size: 30px;">
                <div class="row" >
                    <div class="col-md-12">
                        <b><u>Workshop Question</u> :  {{$workshop->question}}</b>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="container" style="font-size: 30px;">
                <div class="row">
                    <div class="col-md-12">
                        <b><u>Number of Answers</u> : {{$workshop->answerd}} Out of {{$workshop->nb_of_participated}}</b>
                    </div>
                </div>
            </div>
        </div>
        @if(isset($error))
        <div>
            <div class="container" style="font-size: 30px;">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                    <div class='alert alert-danger' style="text-align: center; font-size: 40px;">
                            <h5>{{$error}}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div>
            <div class="container"  style="text-align:center;" >
                <div class="row" style=" padding-top: 5%">
                    <div class="col-md-12">
                        <table style="width: 100%;" id="table" >

                            @if(isset($answers))
                                <tr><th style="text-align:center; Width: 20%">User ID</th><th style="text-align:center;">Answer</th></tr>
                                @foreach($answers as $CA)
                                    <tr>
                                        <td>{{$CA->user_id}}</td>
                                        <td>{{$CA->idea}}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <br/>
        <br/>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="{{URL::to('/startshuffle')}}">
                        {{ csrf_field() }}
                        {{method_field('PUT')}}
                        @if(($workshop->shuffle_stage+1)>5)
                        <input type="submit" class="btn btn-primary" value="Start Grouping Stage" name="submit">
                        @elseif($workshop->shuffle_stage==0)
                        <input type="submit" class="btn btn-primary" value="Start Shuffle Stage" name="submit">
                        @else
                        <input type="submit" class="btn btn-primary" value="Start Shuffle Stage {{$workshop->shuffle_stage+1}}" name="submit">
                        @endif
                        <input type="hidden" name='key' value="{{$workshop->key}}"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>


<style>
#table {
  border: 2px solid black;
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#table td, #table th {
  border: 1px solid black;
  padding: 8px;
}

#table tr:nth-child(even){background-color: #f2f2f2;}

#table tr:hover {background-color: #ddd;}

#table th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #ddd;
}
</style>