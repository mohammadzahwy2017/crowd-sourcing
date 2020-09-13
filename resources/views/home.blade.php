@extends('layouts.app')




@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2" id="div1">
            {{-- <div class="panel panel-default"> --}}
                <div class="panel-heading"><h1>Welcome {{auth()->User()->name}}</h1></div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            {{-- </div> --}}
            <div class="container" id="div2">
                <table style="border:0px;">
                    <tr>
                <td style="padding:17px;"><form method="post" action="{{URL::to('workshop/create')}}">
                    {{csrf_field()}}
                    <input type="submit" value="Create Workshop">
                </form></td>
                <td style="padding:17px;">
                <form method="post" action="{{URL::to('workshop/login')}}">
                    {{ csrf_field()}}
                    <input type="submit" value="Enter a workshop">
                </form>
            </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

<style>
#div1{
    text-align: center;

}

#div2{
    padding-left:26%;
}
</style>