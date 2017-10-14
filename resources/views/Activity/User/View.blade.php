@extends('master')
@section('body')
    <div class="container">
        <div class="row">
            <p class="header login-title"><strong>Users</strong></p>
            <hr/>
            <br/>

            <div class="col-12">
                @include('Partials._message')
            </div>
            <div class="col-12">
                <a href="{{route('addUsers')}}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add Users</a>
                <br/>
                <br/>
                <table class="table table-responsive">
                    <thead>
                    <th>S/N</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Access</th>
                    <th>Action</th>
                    </thead>
                    <?php $i = 1?>
                    <tbody>
                    @foreach($users as $u)
                        <tr class="@if($u->access) alert-success @else alert-danger @endif">
                            <td>{{$i++}}</td>
                            <td>{{$u->email}}</td>
                            <td>{{$u->role->role}}</td>
                            <td>@if($u->access) True @else False @endif</td>
                            <td>
                                <a href="{{route('revokeUser',['token' => encrypt($u->email)])}}" data-toggle="tooltip" title="Revoke Access" class="btn @if($u->access) btn-danger @else btn-success @endif"><i class="fa fa-universal-access"></i></a>
                                <a href="{{route('editUser',['token' => encrypt($u->id)])}}" data-toggle="tooltip" title="Edit User" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                                <a href="{{route('deleteUser',['token' => encrypt($u->id)])}}" data-toggle="tooltip" title="Delete User" onclick="return confirm('This Process cannot be undone and you can\'t re-use this email for another purpose on this site. Do you want to continue?');" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection