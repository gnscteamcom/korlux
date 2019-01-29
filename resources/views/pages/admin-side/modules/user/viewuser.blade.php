@extends('layouts.admin-side.default')


@section('title')
@parent
Master User
@stop


@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Pengguna</h1>
    </div>
</div>


@if(Session::has('msg'))
<div class="row">
    <div class="col-lg-12">
        <div class="form-group text-success">
            {!! '<b>' . Session::get('msg') . '</b>' !!}
        </div>
    </div>
</div>
@endif


@if(Session::has('err'))
<div class="row">
    <div class="col-lg-4">
        <div class="form-group text-danger">
            {!! '<b>' . Session::get('err') . '</b>' !!}
        </div>
    </div>
</div>
@endif



<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                Pengguna
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <div class="col-md-6 margin-bottom-20">
                        <form method="post" action="{{ URL::to('search/searchuser') }}">
                            {!! csrf_field() !!}
                            <div class="col-lg-12">
                                <div class="input-group">
                                    <input type="text" name="search" id="search" class="form-control" required="required" autofocus="autofocus" placeholder="Username" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit">Cari</button>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>

                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Detail</th>
                                <th>Ganti Status</th>
                                <th class="col-sm-2">Username</th>
                                <th class="col-sm-2">Nama</th>
                                <th class="col-sm-3">Alamat</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $i = 1;
                            ?>
                            @foreach($users as $user)
                            <?php
                            $name = '';
                            $address = '';
                            $status = '';

                            if ($user->usersetting) {
                                $name = $user->usersetting->first_name . ' ' . $user->usersetting->last_name . '<br />' .
                                        $user->usersetting->jenis_kelamin;
                                $address = $user->usersetting->alamat . '<br />' .
                                        $user->usersetting->kecamatan . '<br />' .
                                        $user->usersetting->kodepos . '<br />' .
                                        $user->usersetting->hp;
                                if ($user->usersetting->email) {
                                    $address .= '<br />' . $user->usersetting->email;
                                }
                            }
                            ?>
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>
                                    <a href="{{ URL::to('userdetail/' . $user->id) }}" title="Rincian Pengguna"><i class="fa fa-fw fa-2x fa-info-circle"></i></a>
                                </td>
                                <td>
                                    <select class="form-control change-status" data-user-id="{{ $user->id }}">
                                        @if($user->usersetting)
                                        <option value="{{ $user->usersetting->status_id }}" disabled selected>{{ $user->usersetting->status->status }}</option>
                                        @else
                                        <option value="" disabled selected> Please Choose </option>
                                        @endif
                                        @foreach($statuses as $status_change)
                                        <option value="{{ $status_change->id }}">{{ $status_change->status }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    {{ $user->username }}
                                </td>
                                <td>
                                    {!! $name !!}
                                </td>
                                <td>
                                    {!! $address !!}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $users->links() !!}


                </div>
            </div>
        </div>
    </div>
</div>
</div>
@stop

@section('script')
<link rel="stylesheet" href="{{ asset('ext/css/toastr.min.css') }}">
<script type="text/javascript" src="{{ asset('ext/js/toastr.min.js') }}"></script>
<script>
    $('.change-status').change(function () {
        $.post(
                "/user/changestatus",
                {
                    _token: $('input[name=_token]').val(),
                    user_id: $(this).attr('data-user-id'),
                    status_id: $(this).val()
                },
                function (data) {
                    data = JSON.parse(data);
                    if (data.result == 0) {
                        toastr.error(data.msg);
                    } else {
                        toastr.info(data.msg);
                    }
                });
    });
</script>
@stop