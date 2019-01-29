<!doctype html>
<html>
    <head>
        @include('includes.login.head')
    </head>
    
    <body>
        <div class="container">
            
            <div id="main" class="row">
                
                    @yield('content')
                
            </div>
            
            <div id="footer">
                <div class="container text-center">
                    @include('includes.login.footer')
                </div>
            </div>
        </div>
        
        
    </body>
</html>