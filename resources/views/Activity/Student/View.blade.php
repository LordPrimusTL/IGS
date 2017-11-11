@extends('master')
@section('body')
    <div class="container">
        <div class="row">
            <div class="col-12"><p class="header login-title"><strong>Students</strong></p></div>
            <hr/>
            <br/>

            <div class="col-12">
                @include('Partials._message')
            </div>
            <div class="col-lg-12 form-inline">
                <div class="col-lg-2">
                    <a href="{{route('studentAction',['token' => encrypt(1)])}}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add Student</a>
                </div>
                <div class="col-lg-5"></div>
                <div class="col-lg-5">
                    <form action="{{route('searchStudent')}}" method="post" class="form-inline">
                        {{csrf_field()}}
                        <div class="form-group">
                            <input type="text" required style="margin-right: 5px;" class="form-control" name="key" id="key" placeholder="Search Students Here..." value="{{$key == null ? '' : $key}}"/>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                            <a href="{{route('viewStudent')}}" class="btn btn-link">All Students</a>
                        </div>
                    </form>
                </div>
            </div>
            <br/>
            <div class="col-lg-12">
                <br/>
                <table class="table table-responsive">
                    <thead>
                    <th>S/N</th>
                    <th>Adm-ID</th>
                    <th>Full Name</th>
                    <th>Gender</th>
                    <th>DOB</th>
                    <th>Parent Phone Number</th>
                    <th>Status</th>
                    <th>Action</th>
                    </thead>
                    <?php $i = 1?>
                    <tbody>
                    @foreach($stud as $u)
                        <tr class="@if($u->s_id == 1) alert-success @else alert-warning @endif">
                            <td>{{$i++}}</td>
                            <td>{{$u->adm_id}}</td>
                            <td>{{$u->fullname}}</td>
                            <td>{{$u->gender}}</td>
                            <td>{{$u->dob}}</td>
                            <td>{{$u->parent_phone_number}}</td>
                            <td>{{$u->stat->name}}</td>
                            <td>
                                <a href="{{route('viewPaymentID',['col' => encrypt('stud_id'),'val' => encrypt($u->id)])}}" data-toggle="tooltip" title="view Payment" class="btn btn-success btn-sm"><i class="fa fa-money"></i></a>
                                <a href="{{route('studentActionAdd',['token' => encrypt($u->id)])}}" data-toggle="tooltip" title="Add Payment" class="btn btn-info btn-sm"><i class="fa fa-credit-card"></i></a>
                                <a href="{{route('studentActionEdit',['id' => encrypt($u->id)])}}" data-toggle="tooltip" title="Edit Student" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                                @if(\App\Helpers\AuthCheck::Admin())
                                    <a href="{{route('studentActionDelete',['id' => encrypt($u->id)])}}" data-toggle="tooltip" title="Delete Student" onclick="return confirm('This Process cannot be undone. Do you want to continue?');" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="myCenter">
                {{$s == null ? ' ' : $stud->links()}}
            </div>
        </div>
    </div>
@endsection