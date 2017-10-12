<html>
@include('Partials._head')
    <body class="font">
    <div class="page charts-page" style="background-color: white!important;">
        <header>
            @include('Partials._navbar')
            <div class="page-content d-flex align-items-stretch">
                @include('Partials._sidebar')
                @yield('body')
            </div>
        </header>
    </div>
    </body>
</html>
