@extends('master')
@section('body')
    <div class="container">
        <div class="row">
            <p class="header login-title"><strong>Students</strong></p>
            <hr/>
            <br/>

            <div class="col-12">
                @include('Partials._message')
            </div>
            <div class="col-12">
                <a href="{{route('studentAction',['token' => encrypt(1)])}}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add Student</a>
                <br/>
                <br/>
                <table class="table table-responsive">
                    <thead>
                    <th>S/N</th>
                    <th>Adm-ID</th>
                    <th>Fullname</th>
                    <th>Gender</th>
                    <th>DOB</th>
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
                            <td>{{\Carbon\Carbon::parse($u->DOB)->toDateString()}}</td>
                            <td>{{$u->stat->name}}</td>
                            <td>
                                <a href="" data-toggle="tooltip" title="Add Payment" class="btn btn-success"><i class="fa fa-money"></i></a>
                                <a href="{{route('studentActionEdit',['id' => encrypt($u->id)])}}" data-toggle="tooltip" title="Edit Student" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                                @if(\App\Helpers\AuthCheck::Admin())
                                    <a href="{{route('studentActionDelete',['id' => encrypt($u->id)])}}" data-toggle="tooltip" title="Delete Student" onclick="return confirm('This Process cannot be undone. Do you want to continue?');" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection