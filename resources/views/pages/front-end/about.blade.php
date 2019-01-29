@extends('layouts.front-end.layouts')


@section('content')

    <div id="contact" class="container">
      <div class="row margin-bottom">
        <div class="col-md-10 col-md-offset-1">
          <div class="heading">
            <h2>Kontak Kami</h2>
          </div>
        </div>
      </div>
      <div class="row margin-bottom">
        <div class="col-md-4">
          <div class="box-simple">
            <div class="icon"><i class="fa fa-map-marker padding-top-12"></i></div>
            <h3>Alamat</h3>
                @if($address)
                <p>
                    @if(strlen(strip_tags($address->address_1)))
                    {!! $address->address_1  . '<br />' !!}
                    @endif
                    @if(strlen(strip_tags($address->address_2)))
                    {!! $address->address_2  . '<br />' !!}
                    @endif
                    @if(strlen(strip_tags($address->address_3)))
                    {!! $address->address_3  . '<br />' !!}
                    @endif
                    @if(strlen(strip_tags($address->address_4)))
                    {!! $address->address_4  . '<br />' !!}
                    @endif
                </p>
                @endif
          </div>
        </div>
        <div class="col-md-4">
          <div class="box-simple">
            <div class="icon"><i class="fa fa-phone padding-top-12"></i></div>
            <h3>Kontak Kami</h3>
            <p>
                @if($contact)
                <strong>{{ $contact->owner_name }}</strong><br>
                Whatsapp : <strong>{{ $contact->whatsapp }}</strong><br>
                Line : <strong>{{ $contact->line }}</strong><br>
                @endif
            </p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="box-simple">
            <div class="icon"><i class="fa fa-envelope padding-top-12"></i></div>
            <h3>Email</h3>
                @if($contact)
                {{ $contact->email }}<br>
                @endif
          </div>
        </div>
      </div>
      <div class="row text-center margin-bottom">
        <div class="col-md-8 col-md-offset-2">
        @if(Session::has('msg'))
        <div class="col-md-12 text-center">
            <div class="alert alert-success" role="alert" id="msg">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ Session::get('msg') }}
            </div>
        </div>
        @endif
        
        @if(Session::has('err'))
        <div class="col-md-12 text-center">
            <div class="alert alert-success" role="alert" id="err">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ Session::get('err') }}
            </div>
        </div>
        @endif

        @if(!$errors->isEmpty())
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group text-danger">
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        @if($errors->has('nama'))
                            {{ $errors->first('nama') }}
                        @endif
                        @if($errors->has('email'))
                            {{ $errors->first('email') }}
                        @endif
                        @if($errors->has('line_whatsapp'))
                            {{ $errors->first('line_whatsapp') }}
                        @endif
                        @if($errors->has('no_order'))
                            {{ $errors->first('no_order') }}
                        @endif
                        @if($errors->has('pesan'))
                            {{ $errors->first('pesan') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
          <div class="row margin-bottom">
            <div class="col-md-10 col-md-offset-1">
              <div class="heading">
                <h2>Hubungi Kami</h2>
              </div>
            </div>
          </div>
          <form method="post" action="{{ URL::to('sendmessage') }}">
              {!! csrf_field() !!}
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="nama">Nama</label>
                    <input id="nama" name="nama" type="text" class="form-control" required="required">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" class="form-control" required="required">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="line_whatsapp">Line ID / Whatsapp</label>
                    <input id="line_whatsapp" name="line_whatsapp" type="text" class="form-control" required="required">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="no_order">No. Order</label>
                    <input id="no_order" name="no_order" type="text" class="form-control">
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="pesan">Pesan</label>
                    <textarea id="pesan" name="pesan" class="form-control" rows="5" required="required"></textarea>
                  </div>
                </div>
                <div class="col-sm-12 text-center">
                  <button type="submit" class="btn btn-primary col-sm-12"><i class="fa fa-envelope-o"></i> Kirim</button>
                </div>
              </div>
          </form>
        </div>
      </div>
      <!-- /.row-->
    </div>
    </div>

@stop

@section('script')
<script>
      $(document).ready( function() {
        $('#msg').delay(3000).fadeOut();
        $('#err').delay(3000).fadeOut();
      });
</script>
@endsection

@include('includes.admin-side.validation')