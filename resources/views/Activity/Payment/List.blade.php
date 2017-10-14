@extends('master')
@section('body')
    <div class="container">
        <div class="row">
            <p class="header login-title"><strong>Payment List</strong></p>
            <hr/>
            <br/>

            <div class="col-12">
                @include('Partials._message')
            </div>
            <div class="col-6">

                <table class="table table-responsive">
                    <thead>
                    <th>S/N</th>
                    <th>Name</th>
                    <th>Action</th>
                    </thead>
                    <?php $i = 1?>
                    <tbody>
                    @foreach($list as $u)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$u->name}}</td>
                            <td>
                                @if(\App\Helpers\AuthCheck::Admin())
                                    <a href="{{route('deletePayList',['id' => encrypt($u->id)])}}" data-toggle="tooltip" title="Remove Payment From List" onclick="return confirm('This Process cannot be undone. Do you want to continue?');" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-1">
            </div>
            <div class="col-5">
                <form method="POST" action="{{route('addPayList')}}">
                    {{csrf_field()}}
                    <label for="payname"> Payment Name: </label>
                    <div class="form-group">
                        <input class="form-control" type="text" required name="payname" id="payname" placeholder="Payment Name"/>
                    </div>
                    <button type="submit" class="btn btn-default btn-block"><i class="fa fa-plus-circle"></i> Add To Payments</button>
                </form>
            </div>
        </div>
    </div>
@endsection