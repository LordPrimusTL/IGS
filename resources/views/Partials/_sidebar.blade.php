@if(\Illuminate\Support\Facades\Auth::check())
    <nav class="side-navbar">
        <!-- Sidebar Header-->
        <div class="sidebar-header d-flex align-items-center">
            <div class="title">
                <h1 class="h4">{{Auth::user()->email}}</h1>
                <p>IGS Dashboard</p>
            </div>
        </div>
        <!-- Sidebar Navidation Menus--><span class="heading">Main</span>
        <ul class="list-unstyled">
            @if(\Illuminate\Support\Facades\Auth::user()->role_id <= 2)
                <li class="{{Request::is('activity/users') ? "active" : " "}}"> <a href="{{route('users')}}"><i class="fa fa-user-secret"></i>&nbsp;Users</a></li>
            @endif
            <li class="{{Request::is('activity/payment/list') ? "active" : " "}}"> <a href="{{route('paymentList')}}"><i class="fa fa-credit-card"></i>&nbsp;List Of Payments</a></li>
        </ul>
    </nav>
@endif