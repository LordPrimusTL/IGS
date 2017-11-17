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
                    @if($t == 1)
                        <form method="POST" action="{{route('print_class_post')}}">

                        {{csrf_field()}}

                        <label for="sess"> Session: </label>
                        <div class="form-group">
                            <select class="form-control" name="sess" id="sess">
                                @php($sess = \App\SchoolSession::orderByDesc('created_at')->get())
                                @foreach($sess as $s)
                                    <option value="{{$s->id}}">{{$s->session}}</option>
                                @endforeach
                            </select>
                        </div>
                        <label for="term">Term: </label>
                        <div class="form-group">
                            <select name="term" id="term" class="form-control">
                                @php($sess = \App\Term::all())
                                @foreach($sess as $s)
                                    <option value="{{$s->id}}">{{$s->term}}</option>
                                @endforeach
                            </select>
                        </div>
                        <label for="term">Class: </label>
                        <div class="form-group">
                            <select name="class" class="form-control" id="class">
                                @php($sess = \App\SchoolClass::orderByDesc('created_at')->get())
                                @foreach($sess as $s)
                                    <option value="{{$s->id}}">{{$s->class}}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default btn-block"><i class="fa fa-save"></i> Save</button>
                    </form>
                    @endif
                    @if($t == 2)
                        <form method="POST" action="{{route('print_payment_post')}}">

                        {{csrf_field()}}

                        <label for="sess"> Session: </label>
                        <div class="form-group">
                            <select class="form-control" name="sess" id="sess">
                                @php($sess = \App\SchoolSession::orderByDesc('created_at')->get())
                                @foreach($sess as $s)
                                    <option value="{{$s->id}}">{{$s->session}}</option>
                                @endforeach
                            </select>
                        </div>
                        <label for="term">Term: </label>
                        <div class="form-group">
                            <select name="term" id="term" class="form-control">
                                @php($sess = \App\Term::all())
                                @foreach($sess as $s)
                                    <option value="{{$s->id}}">{{$s->term}}</option>
                                @endforeach
                            </select>
                        </div>
                        <label for="term">Payment For: </label>
                        <div class="form-group">
                            <select name="list" class="form-control" id="list">
                                @php($sess = \App\PaymentList::all())
                                @foreach($sess as $s)
                                    <option value="{{$s->id}}">{{$s->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default btn-block"><i class="fa fa-save"></i> Save</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection