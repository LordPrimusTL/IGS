@if(\Illuminate\Support\Facades\Auth::check())
    <nav class="side-navbar">
        <!-- Sidebar Header-->
        <div class="sidebar-header d-flex align-items-center">
            <div class="title">
                <h1 class="h4">{{explode('@',Auth::user()->email)[0]}}</h1>
                <p>IGS Dashboard</p>
            </div>
        </div>
        <!-- Sidebar Navidation Menus--><span class="heading">Main</span>
        <ul class="list-unstyled">
            @if(\Illuminate\Support\Facades\Auth::user()->role_id <= 2)
                <li class="{{Request::is('activity/users') ? "active" : " "}}"> <a href="{{route('users')}}"><i class="fa fa-user-secret"></i>&nbsp;Users</a></li>
                <li class="{{Request::is('activity/session/*') ? "active" : " "}}"> <a href="{{route('viewSession')}}"><i class="fa fa-dashcube"></i>&nbsp;Sessions</a></li>
            @endif
                <li class="{{Request::is('activity/student/view') ? "active" : " "}}"> <a href="{{route('viewStudent')}}"><i class="fa fa-user"></i>&nbsp;Students</a></li>
                <li class="{{Request::is('activity/class/view') ? "active" : " "}}"> <a href="{{route('viewClass')}}"><i class="fa fa-dashboard"></i>&nbsp;Class</a></li>
                <li class="{{Request::is('activity/payment/list') ? "active" : " "}}"> <a href="{{route('paymentList')}}"><i class="fa fa-credit-card"></i>&nbsp;List Of Payments</a></li>
                <li class="{{Request::is('activity/payment/view') ? "active" : " "}}"> <a href="{{route('viewPayment')}}"><i class="fa fa-money"></i>&nbsp;Payments</a></li>
        </ul>
    </nav>
@endif