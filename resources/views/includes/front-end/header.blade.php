<style>
    .sidenav {
        height: 100%; /* 100% Full-height */
        width: 0; /* 0 width - change this with JavaScript */
        position: fixed; /* Stay in place */
        z-index: 1005; /* Stay on top */
        top: 0;
        left: 0;
        background-color: black; /* Black*/
        overflow-x: hidden; /* Disable horizontal scroll */
        padding-top: 60px; /* Place content 60px from the top */
        transition: 0.5s; /* 0.5 second transition effect to slide in the sidenav */
    }

    /* The navigation menu links */
    .sidenav a {
        padding: 8px 8px 8px 32px;
        text-decoration: none;
        font-size: 14px;
        color: #bdbdbd;
        display: block;
        transition: 0.3s
    }

    /* When you mouse over the navigation links, change their color */
    .sidenav a:hover, .offcanvas a:focus{
        color: #f1f1f1;
    }

    /* Position and style the close button (top right corner) */
    .sidenav .closebtn {
        position: absolute;
        top: 0;
        right: 25px;
        font-size: 36px;
        margin-left: 50px;
    }

    /* Style page content - use this if you want to push the page content to the right when you open the side navigation */
    #main {
        transition: margin-left .5s;
        padding: 20px;
    }

    /* On smaller screens, where height is less than 450px, change the style of the sidenav (less padding and a smaller font size) */
    @media screen and (max-height: 450px) {
        .sidenav {padding-top: 15px;}
        .sidenav a {font-size: 18px;}
    }


    /* FIXED HEADER*/
    .fixed-header {
        position: fixed; /* Set the navbar to fixed position */
        top: 0; /* Position the navbar at the top of the page */
        width: 100%; /* Full width */
        margin-top: 0px;
        z-index:99;
    }
</style>
<header class="header">
    <div class="topbar fix-header hidden-xs">
        <div class="container">
            <div class="topbar__content row">
                <div class="col-sm-1 hidden-xs">
                    <div class="topbar__search input-group">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default menu-float"><i class="fa fa-bars"></i></button>
                        </span>
                    </div>
                </div>
                <div class="col-sm-3 hidden-xs">
                    <div class="topbar__search input-group">
                        <span class="input-group-btn">
                            <button type="button" class="btn" onclick="processWebSearch()">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                        <input type="text" placeholder="SEARCH" class="form-control" name="keyword" id="keyword" onkeyup="enterSearch()">
                    </div>
                </div>
                <div class="col-sm-4 topbar__logo hidden-xs">
                    <a href="{{ URL::to('home') }}">
                        <img src="{{ URL::asset('ext/img/logo.png') }}" alt="" class="topbar__logo__normal" height="80px">
                        <img src="{{ URL::asset('ext/img/logo.png') }}" alt="" class="topbar__logo__small" height="80px">
                    </a>
                </div>
                <div class="col-sm-3 hidden-xs">
                    <div class="topbar__cart"><a href="{{ URL::to('cart') }}" class="btn btn-transparent"><i class="fa fa-shopping-cart"></i><span id="cart_count">{{ Cart::instance('main')->count(false) }} barang di keranjang</span></a></div>
                </div>
                @if(!auth()->check())
                <div class="col-sm-1 hidden-xs pull-right">
                    <div class="topbar__search input-group">
                        <span class="input-group-btn">
                            <a href="{{ url('login') }}" type="button" class="btn btn-default"><i class="fa fa-user"></i></a>
                        </span>
                    </div>
                </div>
                @else
                <div class="col-sm-1 hidden-xs pull-right">
                    <div class="topbar__search input-group">
                        <span class="input-group-btn">
                            <a href="{{ url('logout') }}" type="button" class="btn btn-default"><i class="fa fa-sign-out-alt"></i></a>
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div role="navigation" class="navbar navbar-default hidden-lg hidden-md hidden-sm fix-header">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle btn btn-transparent menu-float">
                    <i class="fa fa-bars"></i>
                </button>
                <button type="button" data-toggle="collapse" data-target=".collapsed-search" class="navbar-toggle btn btn-transparent">
                    <i class="fa fa-search"></i>
                    <span class="sr-only">Toggle navigation</span>
                </button>
                @if(!auth()->check())
                <a href="{{ url('login') }}" class="navbar-toggle btn btn-transparent">
                    <i class="fa fa-user"></i>
                </a>
                @else
                <a href="{{ URL::to('cart') }}" class="navbar-toggle btn btn-transparent">
                    <i class="fa fa-shopping-cart"></i>
                </a>
                <a href="{{ url('logout') }}" class="navbar-toggle btn btn-transparent">
                    <i class="fa fa-sign-out"></i>
                </a>
                @endif
            </div>
            <div class="collapsed-search collapse">
                <div class="topbar__search input-group">
                    <input type="text" placeholder="SEARCH" class="form-control" name="keyword" id="keyword2"><span class="input-group-btn">
                        <button type="button" class="btn" onclick="processMobileSearch()"><i class="fa fa-search"></i></button></span>
                </div>
            </div>
        </div>
    </div>

    <?php
        $brands = App\Brand::orderBy('brand')
                ->select('id', 'brand', 'initial')
                ->get();
        $contact = \App\Contact::first();
        $categories = \App\Category::orderBy('position')->select('id', 'category')->get();
        $external_links = \App\Externallink::orderBy('name')->get();
    ?>
    <div id="side-nav" class="sidenav" style="font-weight: bolder;">
        <a href="#" id="menu-close-float" class="pull-right"><i class="fa fa-fw fa-times"></i></a>
        <a href="{{ URL::to('products') }}">Produk</a>
        <a href="{{ URL::to('stock') }}">Stok</a>
        <a href="{{ URL::to('howto') }}">Cara Belanja</a>
        <a href="{{ URL::to('reseller') }}">Reseller</a>
        <a href="{{ URL::to('about') }}">Kontak Kami</a>
        @if(auth()->check())
        <a href="{{ URL::to('paymentconfirmation') }}">Konfirmasi Pembayaran</a>
        <a href="{{ URL::to('history') }}">Order Saya</a>
        <a href="{{ URL::to('requestrefund') }}">Request Refund</a>
        <a href="{{ URL::to('profile') }}">Profil</a>
        <a href="{{ URL::to('logout') }}">Keluar</a>
        @else
        <a href="{{ URL::to('login') }}">Login / Registrasi</a>
        @endif
        <hr>

        @if(auth()->check())
        @if(auth()->user()->usersetting->status_id > 1)
        <a href="{{ url('pricelist') }}"><b>PRICE LIST</b></a>
        <hr>
        @endif
        @endif

        <a href="{{ url('show/0/0/0/0/sale') }}">SALE</a>
        <a href="{{ url('show/0/0/0/0/paket') }}">PAKET</a>
        <hr>
        <label style="margin-left: 3px; color:white;"> Brand<span class="badge pull-right" style="margin-left: 150px;" id="togglebrand"><i class="fa fa-fw fa-plus"></i></span></label>
        <div id="brand-list" style="display:none;">
        @foreach($brands as $brand)
        <a href="{{ URL::to('show/' . $brand->id . '/0/0/0') }}">
            {{ $brand->brand }}
            <span class="badge pull-right">{!! ' ' . $brand->availableProducts()->count() !!}</span>
        </a>
        @endforeach
        </div>
        <hr>
        <label style="margin-left: 3px; color:white;"> Kategori</label>
        @foreach($categories as $category)
        <a href="{{ URL::to('show/0/' . $category->id . '/0/0') }}">
            {{ $category->category }}
            <span class="badge pull-right togglecategory" data-menuid="#category{{ $category->id }}"><i class="fa fa-fw fa-plus"></i></span>
        </a>
        <div style="margin-left: 30px; display:none;" id="category{{ $category->id }}">
            @foreach($category->subcategories as $subcategory)
            <a href="{{ URL::to('show/0/0/' . $subcategory->id . '/0') }}">
                {{ $subcategory->subcategory }}
                <span class="badge pull-right">{!! ' ' . $subcategory->availableProducts()->count() !!}</span>
            </a>
            @endforeach
        </div>
        @endforeach
        @if($contact)
        <hr>

        @foreach($external_links as $link)
        <a href="{{ url('extlink/redirect/' . $link->link) }}" target="_blank">{{ $link->name }}</a>
        @endforeach
        @if($external_links->count() > 0)
        <hr>
        @endif
        <label style="margin-left: 3px; color:white;"> Info</label>
        <a href="#">{!! $contact->info !!}</a>
        @endif
    </div>

</header>

<script>
    function enterSearch() {
        if (event.keyCode == 13) {
            processWebSearch();
        }
    }

    function processWebSearch() {
        var keyword = $('#keyword').val();
        submitSearch(keyword);
    }

    function processMobileSearch() {
        var keyword = $('#keyword2').val();
        submitSearch(keyword);
    }

    function submitSearch(keyword) {
        if (keyword != "") {
            var url = "{{ URL::to('/') }}" + "/show/0/0/0/0/" + keyword;
            window.location.replace(url);
        }
    }

</script>
