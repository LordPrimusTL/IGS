@extends('master')
@section('body')
    <div class="container">
        <div class="row">
            <p class="header login-title"><strong>Sessions</strong></p>
            <hr/>
            <br/>

            <div class="col-12">
                @include('Partials._message')
            </div>
            <div class="col-6">
                <table class="table table-responsive">
                    <thead>
                    <th>S/N</th>
                    <th>Session</th>
                    </thead>
                    <?php $i = 1?>
                    <tbody>
                    @foreach($ses as $u)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$u->session}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-1">
            </div>
            <div class="col-5">
                <form method="POST" action="{{route('addSession')}}">
                    {{csrf_field()}}
                    <label for="payname"> Session </label>
                    <div class="form-group">
                        <input class="form-control" type="text" required name="sess" id="sess" placeholder="E.g 2016/2017"/>
                    </div>
                    <button type="submit" class="btn btn-default btn-block"><i class="fa fa-plus-circle"></i> Add To Sessions</button>
                </form>
            </div>
        </div>
    </div>
@endsection