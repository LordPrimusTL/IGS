@extends('master')
@section('body')
    <div class="container">
        <div class="row">
            <p class="header login-title"><strong>{{$title}}</strong></p>
            <hr/>
            <br/>


            <div class="col-12">
                <div class="col-1"></div>
                <div class="col-4">
                    <div class="col-12">
                        @include('Partials._message')
                    </div>
                    <br>
                    <form method="POST" action="{{route('saveUser')}}">
                        {{csrf_field()}}
                        <input type="hidden" id="type" name="type" value="{{$type}}">
                        <label for="email"> Email Address: </label>
                        <div class="form-group">
                            <input class="form-control" type="email" required name="email" id="email" placeholder="Email Address" value="{{$user != null ? $user->email : " "}}"/>
                        </div>

                        <label for="password"> Password: </label>
                        <div class="form-group">
                            <input class="form-control" type="password"  name="password" id="password" placeholder="Password"/>
                        </div>

                        <label for="role"> Role: </label>
                        <div class="form-group">
                            <select class="form-control" name="role" id="role">
                                <?php $rr = \App\Role::all()?>
                                @foreach($rr as $r)
                                    @if($r->id === 1) @else
                                            <option {{$user != null ? $user->role_id == $r->id ? 'selected' : '' : " "}} value="{{$r->id}}">{{$r->role}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <label for="access"> Access: </label>
                        <div class="form-group">
                            <select class="form-control" name="access" id="access">
                                <option value="0" {{$user!= null ? $user->access == false ? 'selected' : ' ' : ''}}>False</option>
                                <option value="1" {{$user!= null ? $user->access ? 'selected' : ' ' : ''}}>True</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-default btn-block"><i class="fa fa-save"></i> Save</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection