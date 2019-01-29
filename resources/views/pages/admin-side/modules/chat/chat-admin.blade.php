@extends('layouts.admin-side.default')


@section('title')
@parent
    Insert Bank
@stop


@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Live Chat</h1>
        </div>
    </div>
        
        
    <div class="row">
        <div class="col-lg-12">
            <div class="col-md-3 margin-bottom-20"></div>
            <div class="col-md-9 margin-bottom-20">
                <div class="col-lg-12">
                    <div class="input-group">
                        <input type="text" name="search" id="search" class="form-control" required="required" autofocus="autofocus" placeholder="Cari Pesan"/>
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" id="clear_keyword">Clear</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div  class="col-sm-12">
                    <div class="col-xs-3">
                        <div class="col-md-12" id="list-user">
                        </div>
                    </div>

                    <div class="col-xs-9">
                      <!-- Tab panes -->
                        <div class="tab-content" id="list-msg">
                            {!! csrf_field(); !!}
                            <input id="sender_name" type="hidden" value=""/>
                            <input id="conversation_id" type="hidden" value="0"/>
                            <input id="is_user" type="hidden" value="0"/>
                            <input type="hidden" name="search_keyword" id="search_keyword" value=""/>
                            <div class="tab-pane active">
                                <div class="row margin-bottom-20">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="conversations" id="label-conversations">Pilih user di sebelah kiri untuk memulai chat</label>
                                            <div id="conversations" class="row col-md-12 col-xs-12 col-sm-12 back-color-white fg-red chat-div">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-center">
                                        <div class="input-group">
                                            <input id="chats" name="chats" type="text" class="form-control" placeholder="pilih user untuk memulai chat" disabled="disabled">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" id="send_chats" type="button" disabled="disabled"><i class="glyphicon glyphicon-send"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group margin-top-10" id="div-btn-delete">
                                            <input type="button" data-toggle="modal" data-target="#myModal" class="col-md-12 form-control btn-danger" id="end_chats" value="Menutup chat" disabled="disabled">
                                        </div>
                                    </div>
                                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="0" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title" name="">Chat Deletion Confirmation</h4>
                                                </div>
                                                <div class="modal-body">
                                                    Anda yakin mau menghapus chat ini?<br />
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="col-md-12">
                                                        <div id="delete-link" class="col-md-2 pull-right">
                                                        </div>
                                                        <input type="button" class="btn btn-default col-md-2 pull-right" data-dismiss="modal" value="Batal"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('includes.admin-side.validation')
@stop

@section('css')
<link rel="stylesheet" href="{{ URL::asset('ext/css/front-end/plugschat.css') }}">
@endsection

@section('script')

<script type="text/javascript" src="{{ URL::asset('ext/js/custom/plugschat.js') }}"></script>

@endsection