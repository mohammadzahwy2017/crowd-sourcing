@extends('layouts.app')



@section('content')
<div class="container">
    <div style="margin-top: 15%" class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login to Workshop</div>

                <div class="panel-body">
                    <form  class="form-horizontal" method="POST" action="{{ URL::to('/workshop/join_workshop') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="key" class="col-md-4 control-label">Workshop Key</label>

                            <div class="col-md-6">
                                <input id="key" type="text" class="form-control" name="key" value="{{ old('key') }}" required autofocus>

                                @if ($errors->has('key'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('key') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>
                            </div>
                        </div>
                        @if(isset($error))
                            <div class='alert alert-danger' style="text-align: center; font-size: 30px;">
                                <h5 >{{$error}}</h5>
                            </div>
                            @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
