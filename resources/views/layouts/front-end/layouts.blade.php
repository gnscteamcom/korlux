<!doctype html>
<html>
    <head>
        <title>Korean Luxury ::</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="robots" content="all,follow">
        <!-- Bootstrap and Font Awesome css-->
        <!-- we use cdn but you can also include local files located in css directory-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <!-- Google fonts - Montserrat for headings, Raleway for copy-->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,700">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700">
        <!-- owl carousel-->
        <link rel="stylesheet" href="{{ URL::asset('ext/css/front-end/style.default.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('ext/css/front-end/r-style.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('ext/css/front-end/plugschat.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('ext/css/t2o-color.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('ext/css/t2o-default.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('ext/css/custom/menu.css') }}">
        <link rel="shortcut icon" href="favicon.ico">
        
        
        <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
                <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        
        @yield('css')
        
        
    </head>

    
    
    <body id="home">
        <div id="top-header" style="margin-top:120px; display:none;"></div>

        <a href="#0" class="cd-top">Top</a>
        
        @include('includes.front-end.header')
        
        <div id="wrap" style="margin-top:50px">
            
            @yield('content')
            
            @include('includes.front-end.footer')
            
        </div>
        
        
        
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js" ></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" ></script>
        <script type="text/javascript" src="{{ URL::asset('ext/js/front-end/placeholders.min.js') }}"></script>
        
        <script type="text/javascript" src="{{ URL::asset('ext/js/front-end/jquery.scrollTo.min.js') }}" ></script>
        <script type="text/javascript" src="{{ URL::asset('ext/js/front-end/jquery.cookie.js') }}" ></script>
        <script type="text/javascript" src="{{ URL::asset('ext/js/custom/plugschat.js') }}" ></script>
        
        <script type="text/javascript">
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })
            
            $('.menu-float').click(function(){
                document.getElementById("side-nav").style.width = "250px";
            });
            
            
            $(document).on('touchstart click', '#home', function(e){
                if(e.target.id == "side-nav"){
                    return ;
                }
                
                if($(e.target).closest('#side-nav').length)
                    return;  
                
                if($('#side-nav').width() == '250'){
                    document.getElementById("side-nav").style.width = "0";
                }
            });
            
            $(document).on('touchstart click', '#menu-close-float', function(e){
                if($('#side-nav').width() == '250'){
                    document.getElementById("side-nav").style.width = "0";
                }
            });
            
            $(document).on('scroll', function(){
                var currentPosition = $(document).scrollTop();
                if(currentPosition >= 100){
                    $('.fix-header').addClass('fixed-header');
                    $('#top-header').attr('style', 'margin-top:120px; display:block;');
                }
                else{
                    $('.fix-header').removeClass('fixed-header');
                    $('#top-header').attr('style', 'display:none;');
                }
            });
            
            //Buat bagian sidebar
            $(document).on('touchstart click', '.togglecategory', function(e){
                e.preventDefault();
                
                var subcategory = $(this).attr('data-menuid');
                if($(subcategory).css('display') == 'none'){
                    $(subcategory).removeAttr();
                    $(subcategory).attr('style', 'margin-left:30px; display:block;');
                }
                else{
                    $(subcategory).removeAttr();
                    $(subcategory).attr('style', 'margin-left:30px; display:none;');
                }
            });
            
            //Buat bagian sidebar
            $(document).on('touchstart click', '#togglebrand', function(e){
                e.preventDefault();
                
                if($('#brand-list').css('display') == 'none'){
                    $('#brand-list').removeAttr();
                    $('#brand-list').attr('style', 'margin-left:30px; display:block;');
                }
                else{
                    $('#brand-list').removeAttr();
                    $('#brand-list').attr('style', 'margin-left:30px; display:none;');
                }
            });
        </script>
        
        @yield('script')
        @yield('add_script')
        
        
        
    </body>
    

</html>