@extends('master')
@section('body')
    <div class="container">
        <div class="row">
            <p class="header login-title"><strong>Payments</strong></p>
            <hr/>
            <br/>

            <div class="col-12">
                @include('Partials._message')
            </div>
            <div class="col-lg-12 form-inline">
                <div class="col-lg-2">
                    <a href="{{route('payAction',['token' => encrypt(1)])}}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add Payment</a>
                </div>
                <div class="col-lg-2"></div>
                <div class="col-lg-5">
                    <form action="{{route('searchPayment')}}" method="post" class="form-inline">
                        {{csrf_field()}}
                        <div class="form-group">
                            <input type="text" required style="margin-right: 5px; width: 500px;" class="form-control" name="key" id="key" value="{{$key!=null ? $key :''}}" placeholder="Date, ID, Session, Term, Class, Student, For, Amount..."/>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                            <a href="{{route('viewPayment')}}" class="btn btn-link">All Payments</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-12">

                <table class="table table-responsive text-center table-hover">
                    <thead>
                    <th>S/N</th>
                    <th>ID</th>
                    <th>Session</th>
                    <th>Term</th>
                    <th>Class</th>
                    <th>Student</th>
                    <th>For</th>
                    <th>Amount</th>
                    <th>DOP</th>
                    <!--<th>Action</th>-->
                    </thead>
                    <?php $i = 1; $total = 0;?>
                    <tbody>
                    @foreach($pay as $p)
                        <tr>
                            <?php $total = $total + $p->amount;?>
                            <td>{{$i++}}</td>
                            <td>{{$p->p_id}}</td>
                            <td>{{$p->sess->session}}</td>
                            <td>{{$p->term->term}}</td>
                            <td>{{$p->class->class}}</td>
                            <td><a href="{{route('viewStudentID',['token' => encrypt($p->stud->id)])}}" data-toggle="tooltip" title="View Student" class="btn btn-default btn-sm">{{$p->stud->adm_id}}</a> </td>
                            <td>{{$p->list->name}}</td>
                            <td>{{$p->amount}}</td>
                             <td>{{$p->date_of_payment}}</td>
                            <!--<td>
                                <a href="{{route('editUser',['token' => encrypt(1)])}}" data-toggle="tooltip" title="Edit Payment" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                                <a href="{{route('deleteUser',['token' => encrypt(1)])}}" data-toggle="tooltip" title="Delete User" onclick="return confirm('This Process cannot be undone and you can\'t re-use this email for another purpose on this site. Do you want to continue?');" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                            </td>-->
                        </tr>
                    @endforeach
                        <tr class="alert alert-success">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Total:</td>
                            <td>{{'NGN'.$total}}</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection