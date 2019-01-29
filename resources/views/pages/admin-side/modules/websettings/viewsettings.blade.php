@extends('layouts.admin-side.default')


@section('title')
@parent
    Website Configuration
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Pengaturan Website</h1>
        </div>
    </div>

                    
    @if(Session::has('msg'))
    <div class="row">
        <div class="col-lg-12">
            <h3 class="text-success">
                {{ Session::get('msg') }}
            </h3>
        </div>
    </div>
    @endif
        
        
    
    <!--Bagian data kontak-->    
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h2>Kontak</h2>
                </div>
                <div class="panel-body">
                    
                    
                    <form method="post" action="{{ URL::to('updatecontact') }}">
                        {!! csrf_field() !!}
                        
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group text-danger">
                                    @if($errors->has('nama_pemilik'))
                                        {!! $errors->first('nama_pemilik') . '<br />' !!}
                                    @endif
                                    @if($errors->has('email'))
                                        {!! $errors->first('email') . '<br />' !!}
                                    @endif
                                    @if($errors->has('call_center'))
                                        {!! $errors->first('call_center') . '<br />' !!}
                                    @endif
                                    @if($errors->has('line'))
                                        {!! $errors->first('line') . '<br />' !!}
                                    @endif
                                    @if(Session::has('err'))
                                        {{ Session::get('err') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="nama_pemilik">Nama Pemilik</label>
                                    @if(!$contact)
                                    <input type="text" name="nama_pemilik" id="nama_pemilik" class="form-control" placeholder="Owner Name"/>
                                    @else
                                    <input type="text" name="nama_pemilik" id="nama_pemilik" class="form-control" placeholder="Owner Name" value="{{ $contact->owner_name }}"/>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    @if(!$contact)
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Email"/>
                                    @else
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{ $contact->email }}"/>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="call_center">Call Center</label>
                                    @if(!$contact)
                                    <input type="text" class="form-control" name="call_center" id="call_center" placeholder="Call Center"/>
                                    @else
                                    <input type="text" class="form-control" name="call_center" id="call_center" placeholder="Call Center" value="{{ $contact->whatsapp }}"/>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="line">Line</label>
                                    @if(!$contact)
                                    <input type="text" class="form-control" name="line" id="line" placeholder="Line"/>
                                    @else
                                    <input type="text" class="form-control" name="line" id="line" placeholder="Line" value="{{ $contact->line }}"/>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="info">Info</label>
                                    @if(!$contact)
                                    <textarea name="info" id="info" class="form-control" placeholder="Info website" style="resize:none" rows="6"></textarea>
                                    @else
                                    <textarea name="info" id="info" class="form-control" placeholder="Info website" style="resize:none" rows="6">{!! $contact->info !!}</textarea>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <input type="submit" value="Perbarui" class="btn btn-default btn-success btn-block"/>
                            </div>
                        </div>
                        
                    </form>
                    
                    
                </div>
            </div>
        </div>
        
    </div>
        
    
    <hr>
    
    <!--Bagian data alamat-->    
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h2>Alamat</h2>
                </div>
                <div class="panel-body">
                    
                    
                    <form method="post" action="{{ URL::to('updateaddress') }}">
                        {!! csrf_field() !!}
                        
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group text-danger">
                                    @if($errors->has('alamat1'))
                                        {{ $errors->first('alamat1') . '' }}
                                    @endif
                                    @if($errors->has('alamat2'))
                                        {{ $errors->first('alamat2') . '<br />' }}
                                    @endif
                                    @if($errors->has('alamat3'))
                                        {{ $errors->first('alamat3') . '<br />' }}
                                    @endif
                                    @if($errors->has('alamat4'))
                                        {{ $errors->first('alamat4') . '<br />' }}
                                    @endif
                                    @if(Session::has('err'))
                                        {{ Session::get('err') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="alamat1">Alamat 1</label>
                                    @if(!$address)
                                    <input type="text" name="alamat1" id="alamat1" class="form-control" placeholder="Address 1" />
                                    @else
                                    <input type="text" name="alamat1" id="alamat1" class="form-control" placeholder="Address 1" value="{{ $address->address_1 }}"/>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="alamat2">Alamat 2</label>
                                    @if(!$address)
                                    <input type="text" name="alamat2" id="alamat2" class="form-control" placeholder="Address 2" />
                                    @else
                                    <input type="text" name="alamat2" id="alamat2" class="form-control" placeholder="Address 2" value="{{ $address->address_2 }}"/>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="alamat3">Alamat 3</label>
                                    @if(!$address)
                                    <input type="text" name="alamat3" id="alamat3" class="form-control" placeholder="Address 3" />
                                    @else
                                    <input type="text" name="alamat3" id="alamat3" class="form-control" placeholder="Address 3" value="{{ $address->address_3 }}"/>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="alamat4">Alamat 4</label>
                                    @if(!$address)
                                    <input type="text" name="alamat4" id="alamat4" class="form-control" placeholder="Address 4" />
                                    @else
                                    <input type="text" name="alamat4" id="alamat4" class="form-control" placeholder="Address 4" value="{{ $address->address_4 }}"/>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <input type="submit" value="Update Address" class="btn btn-default btn-success btn-block" />
                            </div>
                        </div>
                        
                    </form>
                    
                    
                </div>
            </div>
        </div>
        
    </div>
        
    
    <hr>
        
    
    
    <!--Bagian data tentang terms-->    
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h2>Syarat dan Ketentuan</h2>
                </div>
                <div class="panel-body">
                    
                    
                    <form method="post" action="{{ URL::to('updateterms') }}">
                        {!! csrf_field() !!}
                        
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group text-danger">
                                    @if($errors->has('kebijakan_harga'))
                                        {{ $errors->first('kebijakan_harga') . '<br />' }}
                                    @endif
                                    @if($errors->has('pembayaran'))
                                        {{ $errors->first('pembayaran') . '<br />' }}
                                    @endif
                                    @if($errors->has('pemesanan'))
                                        {{ $errors->first('pemesanan') . '<br />' }}
                                    @endif
                                    @if($errors->has('konfirmasi_pembayaran'))
                                        {{ $errors->first('payment_confirmation') . '<br />' }}
                                    @endif
                                    @if($errors->has('pengiriman'))
                                        {{ $errors->first('pengiriman') . '<br />' }}
                                    @endif
                                    @if($errors->has('pengembalian'))
                                        {{ $errors->first('pengembalian') . '<br />' }}
                                    @endif
                                    @if($errors->has('cara_membeli'))
                                        {{ $errors->first('cara_membeli') . '<br />' }}
                                    @endif
                                    @if($errors->has('reseller'))
                                        {{ $errors->first('reseller') . '<br />' }}
                                    @endif
                                    @if(Session::has('err'))
                                        {{ Session::get('err') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="kebijakan_harga">Kebijakan Harga</label>
                                    @if(!$term)
                                    <textarea name="kebijakan_harga" id="kebijakan_harga" class="form-control" placeholder="Pricing Policy" style="resize:none" rows="6"></textarea>
                                    @else
                                    <textarea name="kebijakan_harga" id="kebijakan_harga" class="form-control" placeholder="Pricing Policy" style="resize:none" rows="6">{!! $term->pricing_policy !!}</textarea>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="pembayaran">Pembayaran</label>
                                    @if(!$term)
                                    <textarea name="pembayaran" id="pembayaran" class="form-control" placeholder="Pembayaran" style="resize:none" rows="6"></textarea>
                                    @else
                                    <textarea name="pembayaran" id="pembayaran" class="form-control" placeholder="Pembayaran" style="resize:none" rows="6">{!! $term->payment !!}</textarea>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="pemesanan">Pemesanan</label>
                                    @if(!$term)
                                    <textarea name="pemesanan" id="pemesanan" class="form-control" placeholder="Order" style="resize:none" rows="6"></textarea>
                                    @else
                                    <textarea name="pemesanan" id="pemesanan" class="form-control" placeholder="Order" style="resize:none" rows="6">{!! $term->order !!}</textarea>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="konfirmasi_pembayaran">Konfirmasi Pembayaran</label>
                                    @if(!$term)
                                    <textarea name="konfirmasi_pembayaran" id="konfirmasi_pembayaran" class="form-control" placeholder="Order" style="resize:none" rows="6"></textarea>
                                    @else
                                    <textarea name="konfirmasi_pembayaran" id="konfirmasi_pembayaran" class="form-control" placeholder="Order" style="resize:none" rows="6">{!! $term->payment_confirmation !!}</textarea>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="pengiriman">Pengiriman</label>
                                    @if(!$term)
                                    <textarea name="pengiriman" id="pengiriman" class="form-control" placeholder="Order" style="resize:none" rows="6"></textarea>
                                    @else
                                    <textarea name="pengiriman" id="pengiriman" class="form-control" placeholder="Order" style="resize:none" rows="6">{!! $term->shipment !!}</textarea>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="pengembalian">Pengembalian</label>
                                    @if(!$term)
                                    <textarea name="pengembalian" id="pengembalian" class="form-control" placeholder="Return" style="resize:none" rows="6"></textarea>
                                    @else
                                    <textarea name="pengembalian" id="pengembalian" class="form-control" placeholder="Return" style="resize:none" rows="6">{!! $term->return !!}</textarea>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="faq">FAQ</label>
                                    @if(!$term)
                                    <textarea name="faq" id="faq" class="form-control" placeholder="FAQ" style="resize:none" rows="12"></textarea>
                                    @else
                                    <textarea name="faq" id="faq" class="form-control" placeholder="FAQ" style="resize:none" rows="12">{!! $term->faq !!}</textarea>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="cara_membeli">Cara Membeli</label>
                                    @if(!$term)
                                    <textarea name="cara_membeli" id="cara_membeli" class="form-control" placeholder="How to Buy" style="resize:none" rows="12"></textarea>
                                    @else
                                    <textarea name="cara_membeli" id="cara_membeli" class="form-control" placeholder="How to Buy" style="resize:none" rows="12">{!! $term->howtobuy !!}</textarea>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="reseller">Reseller</label>
                                    @if(!$term)
                                    <textarea name="reseller" id="reseller" class="form-control" placeholder="Reseller" style="resize:none" rows="12"></textarea>
                                    @else
                                    <textarea name="reseller" id="reseller" class="form-control" placeholder="Reseller" style="resize:none" rows="12">{!! $term->reseller !!}</textarea>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <input type="submit" value="Perbarui" class="btn btn-default btn-success btn-block" />
                            </div>
                        </div>
                        
                    </form>

                </div>
            </div>
        </div>
        
    </div>
        
    
    
</div>
@stop


@section('script')
        
    
    <!--Tiny MCE-->
    <script type="text/javascript" src="ext/js/plugins/tinymce/tinymce.min.js"></script>

    
    <script>

        tinymce.init({
            selector: "textarea"
        });
        
    </script>
    
@stop