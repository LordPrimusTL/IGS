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
                    <form method="POST" action="{{route('saveStudent')}}">

                        {{csrf_field()}}

                        <input type="hidden" id="type" name="type" value="{{$type}}">
                        @if($stud != null)
                            <input type="hidden" id="id" name="id" value="{{$stud->id}}"/>
                        @endif
                        <label for="adm_id"> Admission ID: </label>
                        <div class="form-group">
                            <input class="form-control" type="text" required name="adm_id" id="adm_id" placeholder="Admission ID" value="{{$stud != null ? $stud->adm_id : ""}}"/>
                        </div>
                        <label for="fullname"> Full Name: </label>
                        <div class="form-group">
                            <input class="form-control" type="text" required name="fullname" id="fullname" placeholder="Full Name" value="{{$stud != null ? $stud->fullname : ""}}"/>
                        </div>

                        <label for="password"> Gender: </label>
                        <div class="form-group form-inline">
                            <p class="col-10"> <input class="form-control col-2" type="radio"  name="gender" id="gender" value="Female" {{$stud != null ? $stud->gender === 'Female' ? 'checked' : '' : 'checked' }}/> Female </p><br/>
                            <p class="col-10"> <input class="form-control col-2" type="radio"  name="gender" id="gender" value="Male" {{$stud != null ? $stud->gender === 'Male' ? 'checked' : '' : '' }}/> Male </p>
                        </div>

                        <label for="dob">Date Of Birth: </label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="dob" id="dob" placeholder="DD/MM/YYYY" value="{{$stud != null ? $stud->dob : ""}}"/>
                        </div>
                        <label for="parent_phone_number">Parent Phone Number: </label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="parent_phone_number" id="parent_phone_number" placeholder="Parent Phone Number" value="{{$stud != null ? $stud->parent_phone_number : ""}}"/>
                        </div>

                        <label class="form-control-label" for="status">Status: </label>
                        <div class="form-group">
                            <select class="form-control" id="status" name="status">
                                <option value="">Select Status</option>
                                <?php $s = \App\StudStatus::all()?>
                                @foreach($s as $e)
                                    <option {{$stud != null ? $stud->s_id == $e->id ? 'selected' : '' : ""}} value="{{$e->id}}">{{$e->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default btn-block"><i class="fa fa-save"></i> Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection