@extends('master')
@section('body')
    <div class="container">
        <div class="row">
            <p class="header login-title"><strong>{{$title}}</strong></p>
            <hr/>
            <br/>

            <div class="col-12">
                @include('Partials._message')
            </div>
            <div class="col-12">
                <div class="col-1"></div>
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <br>
                    <form method="POST" action="{{route('passwordChangePost')}}">
                        {{csrf_field()}}
                        <label for="old_pass"> Current Password: </label>
                        <div class="form-group">
                            <input class="form-control" type="password" name="old_pass" id="old_pass" placeholder="Current Password"/>
                        </div>
                        <label for="new_pass">New Password: </label>
                        <div class="form-group">
                            <input class="form-control" type="password" name="new_pass" id="new_pass" placeholder="New Password"/>
                        </div>
                        <label for="conf_new_pass">Confirm New Password: </label>
                        <div class="form-group">
                            <input class="form-control" type="password" name="conf_new_pass" id="conf_new_pass" placeholder="Confirm New Password"/>
                        </div>
                        <button type="submit" class="btn btn-default btn-block"><i class="fa fa-save"></i> Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection