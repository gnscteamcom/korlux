<footer class="footer">
    <div class="footer__copyright">
        <div class="container">
            <div class="row" id="div-conv">
                <div class="col-md-5 col-xs-12 pull-right">
                    <div class="pull-right chat-bottom col-md-5 col-xs-12 chat-index">
                        <input id="is_user" name="is_user" type="hidden" value="1"/>
                        <div id="info">
                            <div class="col-md-8 col-xs-12 col-md-offset-2 chat-background">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-10 col-xs-10">
                                            <label for="conversations">Info</label>
                                        </div>
                                        <div class="col-md-2 col-xs-2">
                                            <a class="cursor-pointer hide-conv">x</a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="row col-md-12 col-xs-12 back-color-white chat-div" style="color:black;">
                                            Dengan mengklik tombol di bawah ini, maka Anda akan langsung terhubung dengan WhatsApp kami. Terima kasih.<br><br>
                                            Admin online:<br>
                                            Hari: Senin-Sabtu<br>
                                            Jam: 10:00-17:00<br><br>
                                            Selain waktu diatas dan tanggal merah: LIBUR/tidak balas sama sekali
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <a href="https://api.whatsapp.com/send?phone=6289687877775" target="_blank" class="btn btn-primary col-sm-12 col-xs-12 margin-bottom-10">Lanjutkan via WhatsApp</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <a href="{{ URL::to('home') }}">Halaman Utama</a>
                    :: 
                    <a href="{{ URL::to('about') }}">Tentang Kami</a>
                    :: 
                    <a href="{{ URL::to('howto') }}">Cara Belanja</a>
                    :: 
                    <a href="{{ URL::to('reseller') }}">Reseller</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5 col-xs-12 pull-right">
                    <div class="pull-right chat-bottom col-md-5 col-xs-12">
                        <div class="col-md-8 col-md-offset-2 col-xs-12 link-chat text-center" >
                            <a class="font-link-chat cursor-pointer col-xs-12" id="show-conv">Klik di sini untuk Live Chat</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <p>&copy;2018 Korean Luxury - Powered by <b><a href="https://digitara.id/" target="_blank" style="font-weight: bolder;">Digitara</a></b></p><br>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="social">
                        <?php
                        $socialmedias = \App\Socialmedia::all();
                        ?>
                        @foreach($socialmedias as $socialmedia)
                        @if(strcmp($socialmedia->social_name, "LINE") == 0)
                        <a href="{{ URL::to('http://line.me/ti/p/' . $socialmedia->social_additional_link) }}" data-animate-hover="pulse" class="" target="_blank">
                            <img src="{{ URL::asset('ext/img/socmed/line.png') }}" class="img-resposive" width="50px" height="50px"/>
                        </a>
                        @else
                        <a href="{{ URL::to('http://' . $socialmedia->social_base_link . '/' . $socialmedia->social_additional_link) }}" data-animate-hover="pulse" class="" target="_blank">
                            <img src="{{ URL::asset('ext/img/socmed/ig.png') }}" class="img-resposive" width="50px" height="50px" />
                        </a>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>