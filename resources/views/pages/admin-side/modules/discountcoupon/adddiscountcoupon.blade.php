@extends('layouts.admin-side.default')


@section('title')
@parent
    Tambah Kupon Diskon
@stop


@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Tambah Kupon Diskon</h1>
        </div>
    </div>
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Input data anda
                </div>
                <div class="panel-body">
                    
                    <form method="post" action="{{ URL::to('insertdiscountcoupon') }}">
                        {!! csrf_field() !!}
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="kode_kupon">Kode Kupon</label>
                                    <input type="text" name="kode_kupon" id="kode_kupon" class="form-control" autofocus="autofocus" placeholder="Kode Kupon" required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="untuk_pengguna">Untuk Pengguna</label>
                                    <select class="form-control" name="untuk_pengguna" required="required">
                                    <option value="" disabled selected>-- Silahkan pilih --</option>
                                        @foreach($statuses as $status)
                                        <option value="{{ $status->id }}">{{ $status->status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="tanggal_berlaku">Tanggal Berlaku</label>
                                    <input type="text" name="datepicker1" id="datepicker1" class="form-control" placeholder="Tanggal Berlaku"  required="required">
                                    <input class="form-control" type="hidden" id="tanggal_berlaku" name="tanggal_berlaku" size="30">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="tanggal_berakhir">Tanggal Berakhir</label>
                                    <input type="text" name="datepicker2" id="datepicker2" class="form-control" placeholder="Tanggal Berakhir"  required="required">
                                    <input class="form-control" type="hidden" id="tanggal_berakhir" name="tanggal_berakhir" size="30" visible="hidden">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="nominal_diskon">Nominal Diskon</label>
                                    <input type="number" name="nominal_diskon" id="nominal_diskon" class="form-control" placeholder="Nominal Diskon"  required="required" min="1"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="persentase">Nominal di atas adalah persentase ?</label>
                                    <input type="checkbox" name="persentase" id="persentase"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="jumlah_berlaku">Jumlah Berlaku</label>
                                    <input type="number" name="jumlah_berlaku" id="jumlah_berlaku" class="form-control" placeholder="Jumlah Berlaku" required="required" min="1"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group text-danger">
                                    @if(!$errors->isEmpty())
                                        @if($errors->has('kode_kupon'))
                                            {{ $errors->first('kode_kupon') }}
                                        @endif
                                        @if($errors->has('tanggal_berlaku'))
                                            {{ $errors->first('tanggal_berlaku') }}
                                        @endif
                                        @if($errors->has('tanggal_berakhir'))
                                            {{ $errors->first('tanggal_berakhir') }}
                                        @endif
                                        @if($errors->has('nominal_diskon'))
                                            {{ $errors->first('nominal_diskon') }}
                                        @endif
                                        @if($errors->has('jumlah_berlaku'))
                                            {{ $errors->first('jumlah_berlaku') }}
                                        @endif
                                    @endif
                                    @if(Session::has('err'))
                                        {{ Session::get('err') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="submit" class="btn btn-default btn-success btn-block" value="Tambah"/>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('includes.admin-side.validation')
@stop

@section('script')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">
  $(function() {
    $( "#datepicker1" ).datepicker({
      altField: "#tanggal_berlaku",
      altFormat: "yy-mm-dd"
    });
    $( "#datepicker1" ).datepicker( "option", "dateFormat", "d MM, yy" );
    $( "#datepicker2" ).datepicker({
      altField: "#tanggal_berakhir",
      altFormat: "yy-mm-dd"
    });
    $( "#datepicker2" ).datepicker( "option", "dateFormat", "d MM yy" );
  });
</script>

@stop