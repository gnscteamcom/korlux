@extends('layouts.admin-side.default')


@section('title')
@parent
    Update Kategori
@stop


@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Perbarui Deskripsi Sub Kategori</h1>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Input Data Anda
                </div>
                <div class="panel-body">

                    <form method="post" action="{{ URL::to('updatesubcategory') }}">
                        {!! csrf_field() !!}

                        <input type="hidden" value="{{ $subcategory->id }}" name="subcategory_id" id="subcategory_id" />

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="kategori">Kategori Saat Ini</label>
                                    <input type="text" readonly="readonly" name="current_kategori" id="current_kategori" class="form-control" autofocus="autofocus" value="{{ $subcategory->category->category }}" maxlength="32" placeholder="Sub Kategori" required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="kategori">Kategori</label>
                                    <select name="kategori" id="kategori" class="form-control" required style="width: 100%;">
                                        <option value="" disabled selected> -- Please Choose --</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}"> {{ $category->category }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="subkategori">Sub Kategori</label>
                                    <input type="text" name="subkategori" id="subkategori" class="form-control" autofocus="autofocus" value="{{ $subcategory->subcategory }}" maxlength="32" placeholder="Sub Kategori" required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="posisi">Posisi</label>
                                    <input type="number" name="posisi" id="posisi" class="form-control" autofocus="autofocus" placeholder="Posisi" min="1" required="required" value="{{ $subcategory->position }}" required="required"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group text-danger">
                                    @if(!$errors->isEmpty())
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group text-danger">
                                                <div class="alert alert-danger alert-dismissible" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    @if($errors->has('kategori'))
                                                        {{ $errors->first('kategori') }}
                                                    @endif
                                                    @if($errors->has('subkategori'))
                                                        {{ $errors->first('subkategori') }}
                                                    @endif
                                                    @if($errors->has('posisi'))
                                                        {{ $errors->first('posisi') }}
                                                    @endif
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
                                                        {{ Session::get('err') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                           </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="submit" class="btn btn-default btn-success btn-block" value="Perbarui"/>
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    <script type="text/javascript">
      $('#kategori').select2();
    </script>
@endsection
