@extends('layouts.admin-side.default')


@section('title')
@parent
    Rincian Riwayat
@stop


@section('content')
                        


@if(Session::has('msg'))
<div class="row">
    <div class="col-lg-12">
        <div class="form-group text-success">
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {!! '<b>' . Session::get('msg') . '</b>' !!}
            </div>
        </div>
    </div>
</div>
@endif

@if(Session::has('err'))
<div class="row">
    <div class="col-lg-12">
        <div class="form-group text-danger">
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {!! '<b>' . Session::get('err') . '</b>' !!}
            </div>
        </div>
    </div>
</div>
@endif
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Rincian Riwayat</h1>
        </div>
    </div>
    {!! csrf_field(); !!}

        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Daftar Riwayat Pesanan
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Nomor Invoice</th>
                                    <th>Total Harga</th>
                                    <th>Ongkos Kirim</th>
                                    <th>Diskon Kupon</th>
                                    <th>Diskon Point</th>
                                    <th>Grand Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                
                                <?php
                                    $total_order = 0;
                                ?>
                                @foreach($orderheaders as $orderheader)
                                <?php 
                                    $grandtotal = $orderheader->grand_total + $orderheader->shipment_cost + $orderheader->unique_nominal
                                            +$orderheader->insurance_fee - $orderheader->discount_coupon - $orderheader->discount_point;
                                    
                                    $status_resi = $orderheader->status->status;
                                    if($orderheader->shipment_invoice != null){
                                        $status_resi .= '<br />' . $orderheader->shipment_invoice;
                                    }
                                ?>
                                <tr>
                                    <td>
                                        <a href="{{ URL::to('vieworderdetail/' . $orderheader->id) }}" title="Order Detail"><i class="fa fa-2x fa-fw fa-info-circle"></i></a>                                        
                                    </td>
                                    <td>
                                        {!! date('d F Y', strtotime($orderheader->created_at)) !!}
                                    </td>
                                    <td>
                                        {!! $orderheader->invoicenumber !!}
                                    </td>
                                    <td>
                                        {!! 'Rp. ' . number_format($orderheader->grand_total, 2, ',', '.') !!}
                                    </td>
                                    <td>
                                        {!! 'Rp. ' . number_format($orderheader->shipment_cost, 2, ',', '.') !!}
                                    </td>
                                    <td>
                                        {!! 'Rp. ' . number_format($orderheader->discount_coupon, 2, ',', '.') !!}
                                    </td>
                                    <td>
                                        {!! 'Rp. ' . number_format($orderheader->discount_point, 2, ',', '.') !!}
                                    </td>
                                    <td>
                                        {!! 'Rp. ' . number_format($grandtotal, 2, ',', '.') !!}
                                    </td>
                                    <td>
                                        {!! $status_resi !!}
                                    </td>
                                </tr>
                                
                                <?php 
                                    $total_order += $grandtotal;
                                ?>
                                @endforeach
                                
                                <tr>
                                    <td colspan="6" class="text-right">
                                        <b>Total</b>
                                    </td>
                                    <td colspan="2">
                                        <b>{!! 'Rp. ' . number_format($total_order, 0, ',', '.') !!}</b>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        {!! $orderheaders->links(); !!}
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Daftar Riwayat Point
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        
                        
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Point Bertambah</th>
                                    <th>Point Berkurang</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <?php
                                    $total_added = 0;
                                    $total_used = 0;
                                ?>
                                
                                @foreach($pointhistories as $pointhistory)
                                <tr>
                                    <td>
                                        {!! date('d F Y', strtotime($pointhistory->created_at)) !!}
                                    </td>
                                    <td>
                                        {!! number_format($pointhistory->point_added, 0, ',', '.') !!}
                                    </td>
                                    <td>
                                        {!! number_format($pointhistory->point_used, 0, ',', '.') !!}
                                    </td>
                                </tr>
                                
                                <?php 
                                    $total_added += $pointhistory->point_added;
                                    $total_used += $pointhistory->point_used;
                                ?>
                                @endforeach
                                
                                <tr>
                                    <td>
                                        <b>Total</b>
                                    </td>
                                    <td>
                                        {!! number_format($total_added, 0, ',', '.') !!}
                                    </td>
                                    <td>
                                        {!! number_format($total_used, 0, ',', '.') !!}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Sisa Point</b>
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                        <b>
                                        {!! number_format($total_added - $total_used, 0, ',', '.') !!}
                                        </b>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Daftar Akses Menu
                </div>
                <div class="panel-body">

                    <input type="hidden" id="user_id" value="{{ $user->id }}" />

                    <div class="row" >
                        <div class="col-lg-6">
                            <div class="form-group text-success">
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <span id="msg-area" style="font-weight: bold;">Silahkan Update data di bawah.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h3>Setting Pengguna Dasar</h3>
                    <form method="post" action="{{ url('updateuserconfig') }}">
                        {!! csrf_field(); !!}
                        <input type="hidden" name="user_id" value="{{ $user->id }}" />
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="nama"> Nama Pengguna </label>
                                    <input type="text" class="form-control" name="nama" value="{{ $user->usersetting->first_name }}" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="landing_url"> Landing URL </label>
                                    <input type="text" class="form-control" name="landing_url" value="{{ $user->landing_url }}" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3"></div>
                            <div class="col-lg-6">
                                <input type="submit" class="btn btn-default btn-success" value="Lanjut dan Periksa"/>
                            </div>
                        </div>
                    </form>
                    
                    <h3>Daftar Akses Utama</h3>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="is_owner" class="pointer-hand">
                                    Pemilik Website?
                                </label>
                                <label class="switch">
                                    @if($user->is_owner == 1)
                                    <input type="checkbox" name="is_owner" id="is_owner" value="1" checked>
                                    @else
                                    <input type="checkbox" name="is_owner" id="is_owner" value="1">
                                    @endif
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="login_admin" class="pointer-hand">
                                    Bisa Login Admin?
                                </label>
                                <label class="switch">
                                    @if($user->is_admin == 1)
                                    <input type="checkbox" name="login_admin" id="login_admin" value="1" checked>
                                    @else
                                    <input type="checkbox" name="login_admin" id="login_admin" value="1">
                                    @endif
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <h3>Daftar Akses Menu</h3>
                    @foreach($menus as $menu)
                        @if($menu->submenus->count() > 0)
                            @foreach($menu->submenus as $submenu)
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="submenu{{ $submenu->id }}" class="pointer-hand">
                                            {{ $menu->menu . ' - ' . $submenu->submenu }}
                                        </label>
                                        <label class="switch">
                                            <?php 
                                                $is_menu_exist = \App\Usermenu::where('user_id', '=', $user->id)
                                                        ->where('menu_id', '=', $menu->id)
                                                        ->where('submenu_id', '=', $submenu->id)
                                                        ->first();
                                            ?>
                                            @if($is_menu_exist)
                                            <input type="checkbox" class="menu-checked" name="submenu{{$submenu->id }}" id="submenu{{$submenu->id }}" data-menu="{{ $menu->id }}" data-submenu="{{ $submenu->id }}" value="1" checked>
                                            @else
                                            <input type="checkbox" class="menu-checked" name="submenu{{$submenu->id }}" id="submenu{{$submenu->id }}" data-menu="{{ $menu->id }}" data-submenu="{{ $submenu->id }}" value="1">
                                            @endif
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="menu{{ $menu->id }}" class="pointer-hand">
                                            {{ $menu->menu }}
                                        </label>
                                        <label class="switch">
                                            <?php 
                                                $is_submenu_exist = \App\Usermenu::where('user_id', '=', $user->id)
                                                        ->where('menu_id', '=', $menu->id)
                                                        ->where('submenu_id', '=', 0)
                                                        ->first();
                                            ?>
                                            @if($is_submenu_exist)
                                            <input type="checkbox" class="menu-checked" name="menu{{$menu->id }}" id="menu{{$menu->id }}" data-menu="{{ $menu->id }}" data-submenu="0" value="1" checked>
                                            @else
                                            <input type="checkbox" class="menu-checked" name="menu{{$menu->id }}" id="menu{{$menu->id }}" data-menu="{{ $menu->id }}" data-submenu="0" value="1">
                                            @endif
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach


                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('script')
<style>
.switch {
  position: relative;
  display: block;
  width: 4em;
  height: 1em;
}

/* Hide default HTML checkbox */
.switch input {display:none;}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 13px;
  width: 13px;
  left: 4px;
  bottom: 1px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(30px);
  -ms-transform: translateX(30px);
  transform: translateX(30px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
} 
</style>
    <script>
        var token = $('input[name=_token]').val();
        var user_id = $('#user_id').val();
        $('#login_admin').change(function(){
            var is_checked = $(this).is(':checked');
            
            var is_admin = 0;
            if(is_checked){
                is_admin = 1;
            }
            
            //kirim data
            $.post(
                "/api/rules/admin",
                {
                    _token:token,
                    user_id:user_id,
                    is_admin:is_admin
                },
                function(data){
                    data = JSON.parse(data);
                    
                    showMessage(data.msg);
            });
        });
        
        $('#is_owner').change(function(){
            var is_checked = $(this).is(':checked');
            
            var is_owner = 0;
            if(is_checked){
                is_owner = 1;
            }
            
            //kirim data
            $.post(
                "/api/rules/owner",
                {
                    _token:token,
                    user_id:user_id,
                    is_owner:is_owner
                },
                function(data){
                    data = JSON.parse(data);
                    
                    showMessage(data.msg);
            });
        });
        
        $('.menu-checked').change(function(){
            var is_checked = $(this).is(':checked');
            var menu_id = $(this).attr('data-menu');
            var submenu_id = $(this).attr('data-submenu');
            
            var is_delete = 1;
            if(is_checked){
                is_delete = 0;
            }
            
            //kirim data
            $.post(
                "/api/rules/menuaccess",
                {
                    _token:token,
                    user_id:user_id,
                    is_delete:is_delete,
                    menu_id:menu_id,
                    submenu_id:submenu_id
                },
                function(data){
                    data = JSON.parse(data);
                    
                    showMessage(data.msg);
            });
        });
        
        function showMessage(msg){
            $('#msg-area').text(msg);
            setTimeout(function(){
                $('#msg-area').text('Silahkan Update data di bawah.');
            }, 5000);
        }
    </script>
@stop