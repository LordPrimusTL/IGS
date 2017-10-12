
<header class="header">
    <nav class="navbar">

        <div class="container-fluid">
            <div class="navbar-holder d-flex align-items-center justify-content-between">
                <!-- Navbar Header-->
                <div class="navbar-header">
                    <!-- Navbar Brand --><a href="" class="navbar-brand">
                        <div class="brand-text brand-big hidden-lg-down"><span></span><strong>Ilori</strong> Group Of Schools</div>
                        <div class="brand-text brand-small"><strong>IGS</strong></div>
                    </a>
                    <!-- Toggle Button--><a id="toggle-btn" href="#" class="menu-btn active"><span></span><span></span><span></span></a>
                </div>
                <!-- Navbar Menu -->
                <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
                    <!-- Logout/Login    -->
                    @if(\Illuminate\Support\Facades\Auth::check())
                        <li>@if(Session::has('welcome')) <strong>Welcome, </strong> @endif {{\Illuminate\Support\Facades\Auth::user()->email}}</li>
                        <li class="nav-item"><a href="{{route('logout')}}" class="nav-link logout">Logout<i class="fa fa-sign-out"></i></a></li>
                    @else
                        <li class="nav-item"><a href="{{route('login')}}" class="nav-link logout">Login<i class="fa fa-sign-in"></i></a></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</header>