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
                    <form method="POST" action="{{route('savePayment')}}">
                        {{csrf_field()}}
                        <input type="hidden" id="type" name="type" value="{{$type}}">
                        @if($type == 2) <input type="hidden" id="id" name="id" value="{{$pay->id}}">@endif
                        <label for="stud">Student: </label>
                        <div class="form-group">
                            <select name="stud" id="stud" class="form-control">
                                <option value="">Select Student</option>
                                <?php $stud = \App\Student::all();?>
                                @foreach($stud as $s)
                                    <option value="{{$s->adm_id}}" {{$pay != null ? $pay->stud_id === $s->adm_id ? 'selected' : '' : ''}}>{{$s->fullname}}</option>
                                @endforeach
                            </select>
                        </div>

                        <label for="sess">Session: </label>
                        <div class="form-group">
                            <select required name="sess" id="sess" class="form-control">
                                <?php $stud = \App\SchoolSession::all();?>
                                <option value="">Select Session</option>
                                @foreach($stud as $s)
                                    <option value="{{$s->id}}" {{$pay != null ? $pay->stud_id === $s->id ? 'selected' : '' : ''}}>{{$s->session}}</option>
                                @endforeach
                            </select>
                        </div>

                        <label for="term">Term: </label>
                        <div class="form-group">
                            <select required name="term" id="term" class="form-control">
                                <?php $stud = \App\Term::all();?>
                                <option value="">Select Term</option>
                                @foreach($stud as $s)
                                    <option value="{{$s->id}}" {{$pay != null ? $pay->term_id === $s->id ? 'selected' : '' : ''}}>{{$s->term}}</option>
                                @endforeach
                            </select>
                        </div>

                        <label for="class">Class: </label>
                        <div class="form-group">
                            <select required name="c_id" id="c_id" class="form-control">
                                <?php $stud = \App\SchoolClass::all();?>
                                <option value="">Select Class</option>
                                @foreach($stud as $s)
                                    <option value="{{$s->id}}" {{$pay != null ? $pay->c_id === $s->id ? 'selected' : '' : ''}}>{{$s->class}}</option>
                                @endforeach
                            </select>
                        </div>

                        <label for="for">Payment For: </label>
                        <div class="form-group">
                            <select required name="pl" id="pl" class="form-control">
                                <?php $stud = \App\PaymentList::all();?>
                                <option value="">Select Payment: </option>
                                @foreach($stud as $s)
                                    <option value="{{$s->id}}" {{$pay != null ? $pay->pl_id === $s->id ? 'selected' : '' : ''}}>{{$s->name}}</option>
                                @endforeach
                            </select>
                        </div>



                        <label for="amount"> Amount(NGN): </label>
                        <div class="form-group">
                            <input class="form-control" type="number" required name="amount" id="amount" placeholder="Amount" value="{{$pay != null ? $pay->amount : " "}}"/>
                        </div>

                        <button type="submit"  class="btn btn-default btn-block"><i class="fa fa-save"></i> Save</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection