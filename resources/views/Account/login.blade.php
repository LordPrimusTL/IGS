@extends('master')
@section('body')
    <div class="container">
        <div class="row">
            <div class="col-lg-3 "></div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 center-block">
                <div class="log-in-panel">
                    <div class="card card-1">
                        <p class="login-title">Please Login Here...</p>
                        <hr/>
                        <br/>
                        <div class="row">
                            <div class="col-1">
                            </div>
                            <div class="col-10">
                                @include('Partials._message')
                                <form method="post" action="{{route('loginPost')}}">
                                    {{csrf_field()}}
                                    <label for="email">Email Address: </label>
                                    <div class="form-group">
                                        <input type="email" name="email" id="email" required class="form-control" placeholder="Enter Email Address"/>
                                    </div>

                                    <label for="password">Passsword</label>
                                    <div class="form-group">
                                        <input type="password" name="password" id="password" required class="form-control" placeholder="Enter Password"/>
                                    </div>

                                    <button class="btn btn-default btn-block" type="submit"><i class="fa fa-sign-in"></i> Login</button>
                                </form>
                            </div>
                            <div class="col-1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3"></div>
        </div>
    </div>
@endsection