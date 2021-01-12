<!doctype html>
<html>
    <head>
        @include('includes.admin-side.head')
        @yield('css')
    </head>

    <body>
        <div class="wrapper">
            @include('includes.admin-side.header')
        </div>

        <div id="page-wrapper">
            <div id="main" class="row">

                    @yield('content')

                <footer class="row">
                    @include('includes.admin-side.footer')
                </footer>
            </div>

        </div>

        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script type="text/javascript" src="{{ URL::asset('ext/js/bootstrap-3.3.5/bootstrap.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('ext/js/plugins/metisMenu/metisMenu.min.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('ext/js/admin-side/sb-admin-2.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('ext/js/plugins/dataTables/jquery.dataTables.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('ext/js/plugins/dataTables/dataTables.bootstrap.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('ext/js/plugins/fancyBox/jquery.fancybox.js?v=2.1.5.js') }}"></script>
        @yield('script')

    </body>
</html>
