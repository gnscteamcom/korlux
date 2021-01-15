<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <span class="navbar-brand">
            Welcome, {{ Auth::user()->name }}
        </span>
    </div>



    <!--Menu atas-->
    <ul class="nav navbar-top-links navbar-right">
        @if(auth()->user()->is_owner)
        <li class="dropdown">
            <a href="{{ url('updatedailyprice') }}" title="Update Daily Price">
                <i class="fa fa-money fa-fw"></i>
            </a>
        </li>
        @endif
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li>
                    <a href="{{ URL::to('changepassword') }}"><i class="fa fa-gear fa-fw"></i> Ganti Password</a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="{{ URL::to('logout') }}"><i class="fa fa-sign-out fa-fw"></i> Keluar</a>
                </li>
            </ul>
        </li>
    </ul>




    <!--Menu kiri-->
    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">

                <?php
                    $menus = \App\Menu::join('usermenus', 'menus.id', '=', 'usermenus.menu_id')
                            ->where('usermenus.is_active', '=', 1)
                            ->where('usermenus.user_id', '=', auth()->user()->id)
                            ->select('menus.id', 'menus.menu', 'menus.menu_link', 'menus.menu_icon')
                            ->groupBy('menus.id')
                            ->get();
                ?>

                <li>
                    <a class="active" href="{{ url('home') }}" target="_blank"><i class="fa fa-home fa-fw"></i> Home</a>
                </li>
                @foreach($menus as $menu)
                <li>
                    <a href="{{ url($menu->menu_link) }}"><i class="fa fa-{{ $menu->menu_icon }} fa-fw"></i> {{ $menu->menu }}
                        @if($menu->submenus->count() > 0)
                        <span class="fa arrow"></span>
                        @endif
                    </a>
                    @if($menu->submenus->count() > 0)
                    <ul class="nav nav-second-level">
                        <?php
                            $submenus = \App\Submenu::join('usermenus', 'submenus.id', '=', 'usermenus.submenu_id')
                                    ->where('usermenus.is_active', '=', 1)
                                    ->where('usermenus.user_id', '=', auth()->user()->id)
                                    ->where('usermenus.menu_id', '=', $menu->id)
                                    ->select('submenus.id', 'submenus.submenu', 'submenus.submenu_link')
                                    ->orderBy('submenus.position')
                                    ->get();
                        ?>
                        @foreach($submenus as $submenu)
                            <li>
                                <a href="{{ url($submenu->submenu_link) }}"> {{ $submenu->submenu }}
                                @if($submenu->id == 15)
                                <span class="fa arrow"></span>
                                @endif
                                </a>
                                @if($submenu->id == 15)
                                <ul class="nav nav-third-level">
                                    <?php
                                        $brands = \App\Brand::select('id', 'brand')->orderBy('brand')->get();
                                    ?>
                                    @foreach($brands as $brand)
                                    <li>
                                        <a href="{{ URL::to('viewproduct/' . $brand->id) }}">{{ $brand->brand }}</a>
                                    </li>
                                    @endforeach
                                </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                    @endif
                </li>
                @endforeach

            </ul>
        </div>
    </div>
</nav>
