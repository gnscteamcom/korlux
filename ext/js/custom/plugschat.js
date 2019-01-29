/* 
 * 
 * Licensed v.1.0 Team2One
 * All Source Code belongs to Team2One
 * Revised Date : 13 July 2016
 * 
 */


var token_chat = $('input[name=_token]').val();
var is_user = $('#is_user').val();
var intervalTime = 1000000;
var chatInterval = null;

//hanya untuk kalau user
if(is_user === "1"){

    $('#show-conv').click(function(){
        $('#div-conv').show();
        //retrieveEmailAndName();
    });
    
}
//ini bagian khusus yang owner
else if(is_user === "0"){

//    retrieveEmailAndName();
//    refreshUserList();

    //set per interval untuk refresh list user
//    setInterval(function(){
//        refreshUserList();   
//    }, intervalTime*3);
    
}

//send message kalau dienter
$('#chats').keyup(function(event){
    var keyCode = (event.keyCode ? event.keyCode : event.which);
    
    if(keyCode === 13){
        //kirim chat kalau di-enter
        sendChats();
    }
});

//cari chat kalau dienter
$('#search').keyup(function(event){
    var keyCode = (event.keyCode ? event.keyCode : event.which);
    
    if(keyCode === 13){
        //cari chat kalau di-enter
        searchChats();
    }
});

//hapus keyword search untuk ngulang refresh data
$('#clear_keyword').click(function(){
    $('#search_keyword').val('');
    clearChat();
});

$('#div-conv').hide();

$('.hide-conv').click(function() {
    $('#div-conv').hide();
});

//send message kalau di klik tombol nya
$('#send_chats').click(function(){
    sendChats();
});

//kalau sudah input email dan nama di front-end
$('#btn_proceed').click(function(){
    var email_chat = $('#email_chat').val();
    var name_chat = $('#name_chat').val();
    
    setInitialEmailAndName(email_chat, name_chat);
});

function deleteChat() {
    $.post(
        "chatapi/endconversation",
        {
            conversation_id: $('#conversation_id').val(),
            _token: token_chat
        },
        function(data){
            clearChat();
            disableChat();
            refreshUserList();
        }
    );
}




//###################################
//PRIVATE FUNCTION
//###################################


//fungsi untuk ambil email dari user
function retrieveEmailAndName(){
    $.post(
        "chatapi/retrieveemailandname",
        {
            _token: token_chat
        },
        function(data){
            //berarti sudah login
            if(data.started_email.length > 0 ||
                data.sender_name.length > 0){
                if(is_user == 1){
                    $('#started_email').val(data.started_email);
                    retrieveConversation(data.started_email);
                    afterLogin();
                }
                $('#sender_name').val(data.sender_name);
            }
            else{
                beforeLogin();
            }
        }
    );
}

//fungsi untuk set inisial email dan nama pada saat user tidak login
function setInitialEmailAndName(email_chat, name_chat){
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    beforeLogin();
    
    //validasi email
    if(!regex.test(email_chat)){
        alert("Format Email salah");
    }
    //validasi nama minimal 4 karakter
    else if(name_chat.length < 3){
        alert("Panjang Nama Minimal 3 karakter");
    }
    else{
        //ajax untuk set session nya dengan email dan namanya.
        $.post(
            "chatapi/setsession",
            {
                started_email: email_chat,
                sender_name: name_chat,
                _token: token_chat
            },
            function(data){

                //setelah selesai set session..
                $('#started_email').val(data.started_email);
                $('#sender_name').val(data.sender_name);
                
                //tampilin chat box
                afterLogin();
                retrieveConversation(data.started_email);

            }
        );
    }
}

//fungsi untuk retrieve conversation dari email chat
function retrieveConversation(started_email){
    //ajax buat retrieve conversation_id yang aktif
    $.post(
        "chatapi/activeconversation",
        {
            _token: token_chat,
            started_email: started_email
        },
        function(data){
            //masukkan conversation_id ke hidden input kalau ada
            $('#conversation_id').val(data);

            //ajax buat grab conversation aktif
            $.post(
                "chatapi/initialconversation",
                {
                    conversation_id: data,
                    is_user: is_user,
                    _token: token_chat
                },
                function(data){

                    //tampilkan seluruh chats kalau ada
                    if(data.length > 0){
                        var html = "";
                        $.each(data, function(key, element){
                            if(element.is_owner == 1){
                                html += "<span class='fg-red'>" + element.sender_name + " : " + element.chat + "</span><br>";
                            }
                            else{
                                html += "<span class='fg-blue'>" + element.sender_name + " : ";
                                if(element.filepath != ''){
                                    html += '<a href="' + element.filepath + '" target="_blank">Download File</a>';
                                }
                                else{
                                    html += element.chat;
                                }
                                html += "</span><br>";
                            }
                        });
                        $('#conversations').append(html);
                    }
                    else{
                        $('#conversations').append("<span class='fg-red'>" + 'Hi kak ' + $('#sender_name').val() + ', ada yang bisa dibantu? :)' + "</span><br><br>");
                    }

                    refreshChat();
                    scrollBottom();
                }
            );

        }
    );
}


//fungsi untuk menampilkan chat pertama kali
function revealChat(conversation_id) {
    
    clearChat();
    $('#conversation_id').val(conversation_id);
    
    //ajax buat grab conversation aktif
    $.post(
        "chatapi/initialconversation",
        {
            conversation_id: conversation_id,
            is_user: is_user,
            _token: token_chat
        },
        function(data){

            //tampilkan seluruh chats kalau ada
            if(data.length > 0){
                var html = "";
                $.each(data, function(key, element){
                    if(element.is_owner == 1){
                        html += "<span class='fg-red'>" + element.sender_name + " : " + element.chat + "</span><br>";
                    }
                    else{
                        html += "<span class='fg-blue'>" + element.sender_name + " : ";
                        if(element.filepath != ''){
                            html += '<a href="' + element.filepath + '" target="_blank">Download File</a>';
                        }
                        else{
                            html += element.chat;
                        }
                        html += "</span><br>";
                    }
                });
                $('#conversations').append(html);
            }
            else{
                $('#conversations').append('');
            }

            refreshChat();
            scrollBottom();
            generateDelete(conversation_id);
        }
    );
}

//fungsi untuk melakukan pencarian
function searchChats(){
    var search = $('#search').val();
    if(search.length > 0){
        $('#search').val('');
        $.post(
            "chatapi/searchchat",
            {
                search: search,
                _token: token_chat
            },
            function(data){
                $('#search_keyword').val(data);
                clearChat();
            }

        );
    }
}

function generateDelete(conversation_id) {
    $("#delete-link").html("<a onclick='deleteChat()' href=javascript:void(0);' class='btn btn-primary' data-dismiss='modal'>Hapus</a>");
}

function clearChat() {
    clearInterval(chatInterval);
    $('#conversations').text('');
    $('#chats').val('');
    $('#send_chats').removeAttr('disabled');
    $('#end_chats').removeAttr('disabled');
    $('#chats').removeAttr('disabled');
}

function disableChat() {
    $('#conversations').prop('readonly', 'readonly');
    $('#send_chats').prop('disabled', true);
    $('#end_chats').prop('disabled', true);
    $('#chats').prop('disabled', true);
}

function afterLogin(){
    $('#info').hide();
    $('#start_conversation').show();
}

function beforeLogin(){
    $('#info').show();
    $('#start_conversation').hide();
}

//fungsi untuk kirim chat
function sendChats(){
    var chats = $.trim($('#chats').val());
    var conversation_id = $('#conversation_id').val();
    var sender_name = $('#sender_name').val();

    if(chats.length > 0){
        $('#chats').val('');
        $.post(
            "chatapi/sendchat",
            {
                conversation_id: conversation_id,
                chats: chats,
                sender_name: sender_name,
                is_user: is_user,
                _token: token_chat
            },
            function(data){
                //harus tambahin untuk refresh tampilan text nya..
                // $('#conversations').append(data.sender_name + " : " + data.chat + "\n");
                var html = "";
                if(data.is_owner == 1)
                    html += "<span class='fg-red'>" + data.sender_name + " : " + data.chat  + "</span><br>";
                else
                    html += "<span class='fg-blue'>" + data.sender_name + " : " + data.chat  + "</span><br>";
                $('#conversations').append(html);
                scrollBottom();
            }

        );
    }
}

//ajax buat retrieve list conversation_id yang aktif
function refreshUserList(){
    $.post(
        "chatapi/activeconversationlist",
        {
            _token: token_chat,
            search_keyword: $('#search_keyword').val()
        },
        function(data){

            $('#list-user').empty();

            //bagian untuk generate list user dan tampilkan
            $.each(data, function(key, value){
                var text1 = "<div class='col-md-12'>"
                            + "<a href="
                            + "'#" + value.sender_name + "'"
                            + " class='padding-0 users_list' id='" + value.id + "'>"
                            + value.sender_name
                            + "</a> &nbsp;&nbsp;&nbsp;";

                if(value.count != 0)
                    text1 += "<span class='badge'>"
                            + value.count;
                            + "</span>";

                text1 += ""
                       + "</div>";

                $('#list-user').append(text1);
            });

            //kalau salah satu user di klik, maka tampilkan conversationnya
            $('.users_list').click(function(){
                revealChat($(this).attr('id'));
            });

        }
    );
}

//fungsi untuk refresh chat per sekian detik
function refreshChat(){
    chatInterval = setInterval(function(){
        $.post(
            "chatapi/refreshchat",
            {
                conversation_id: $('#conversation_id').val(),
                is_user: is_user,
                _token: token_chat
            },
            function(data){

                //tampilkan seluruh chats kalau ada
                if(data == '1'){
                    //masuk sini berarti conversationnya sudah end
                    $('#conversations').append("<br><br><span class='fg-red'>Percakapan ini telah ditutup. Terima kasih.</span><br><br>");
                    clearInterval(chatInterval);
                    scrollBottom();
                    disableChat();
                }
                else{
                    if(data.length > 0){
                        $.each(data, function(key, element){
                            //harus tambahin untuk refresh tampilan text nya..
                            var html = "";
                            $('#div-conv').show();
                            if(element.is_owner == 1)
                                html += "<span class='fg-red'>" + element.sender_name + " : " + element.chat  + "</span><br>";
                            else
                                html += "<span class='fg-blue'>" + element.sender_name + " : " + element.chat  + "</span><br>";
                            $('#conversations').append(html);
                            scrollBottom();
                        });
                    }
                }

            }
        );
        
    }, intervalTime);
}

function scrollBottom() {
    $("#conversations").animate({
        scrollTop:$("#conversations")[0].scrollHeight - $("#conversations").height()
    },0);
}

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})