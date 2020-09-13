@extends('layouts.layout');
@section('content')

@if(count($users)>0)
    @foreach ($users as $user)
        <div class="well">
        <h1>{{$user->id}} </h1>
        <hr>
        <small>{{$user->name}}</small>
        </div>
    @endforeach

@endif
    <form action="{{URL::to('/store')}}" method="POST">
    Name <input type="text" name="name">
    Email <input type="text" name="email">
    password <input type="text" name="pass">
    {{-- <input type="hidden" name="token" value="{{csrf_token()}}"/> --}}
    <input type="submit">
</form>

@endsection