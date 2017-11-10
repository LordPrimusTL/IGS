@extends('master')
@section('body')
    <div class="container">
        <div class="row">
            <p class="header login-title"><strong>{{$title}}</strong></p>
            <hr/>
            <hr/>
            <br/>

            <div class="col-12">
                @include('Partials._message')
            </div>
            <div class="col-8">
                <table class="table table-responsive table-hover">
                    <thead>
                    <th>S/N</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Class</th>
                    @if(\App\Helpers\AuthCheck::Admin())<th>Action</th>@endif
                    </thead>
                    <?php $i = 1?>
                    <tbody>
                    @foreach($cls as $p)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{\Carbon\Carbon::parse($p->created_at)->toDateString()}}</td>
                            <td>{{\App\ClassType::find($p->type)->type}}</td>
                            <td>{{$p->class}}</td>
                            <td>
                                <a href="{{route('viewPaymentID',['col' => encrypt('c_id'), 'val' => encrypt($p->id)])}}" class="btn btn-success btn-sm"><i class="fa fa-money"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-4">
                <br/><br/><br/>
                <p><strong>Add Class</strong></p>
                <form method="POST" action="{{route('addClass')}}">
                    {{csrf_field()}}
                    <label for="type">Class Type: </label>
                    <div class="form-group">
                        <select class="form-control" id="type" name="type">
                            <?php $ss = \App\ClassType::all();?>
                            @foreach($ss as $s)
                                <option value="{{$s->id}}">{{$s->type}}</option>
                             @endforeach
                        </select>
                    </div>
                    <label for="name">Class Name: </label>
                    <div class="form-group">
                        <input class="form-control" type="text" required name="name" id="name" placeholder="Class Name"/>
                    </div>
                    <button type="submit" class="btn btn-default btn-block"><i class="fa fa-plus-circle"></i> Add To Class</button>
                </form>
            </div>
        </div>
    </div>
@endsection