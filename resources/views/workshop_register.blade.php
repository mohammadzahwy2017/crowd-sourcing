@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Create Workshop</div>

                <div class="panel-body">
                <form class="form-horizontal" method="POST"  action="{{URL::to('/workshop')}}">
                        {{ csrf_field() }}
                    @if(!isset($errorM))
                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label for="title" class="col-md-4 control-label">Title</label>

                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control" name="title" value="{{ old('title') }}" required autofocus>

                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('nb_of_users') ? ' has-error' : '' }}">
                            <label for="nb_of_users" class="col-md-4 control-label">Number of Users</label>

                            <div class="col-md-6">
                                <input id="nb_of_users" type="text" class="form-control" name="nb_of_users" value="{{ old('nb_of_users') }}" required autofocus>
                                @if(isset($data))
                                        <h5 class='alert alert-danger' style="text-align: center;">{{$data}}</h5>
                                @endif
                                @if ($errors->has('nb_of_users'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nb_of_users') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
                            <label for="description" class="col-md-4 control-label">Description</label>

                            <div class="col-md-6">
                                <textarea style="height: 137px; resize:none" id="description" type="text" class="form-control" name="description" value="{{ old('description') }}" required></textarea>

                                @if ($errors->has('body'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('body') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <input type="hidden" name='key' value = {{$key}}>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Create Workshop
                                </button>
                            </div>
                        </div>
                    @else
                        <div class='alert alert-danger' style="text-align: center; font-size: 40px;">
                            <h5>{{$errorM}}</h5>
                        </div>
                    @endif 
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
