<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Conversation;
use App\Chat;
use Mail;

class ApiChatController extends Controller {

    //fungsi untuk upload file
    public function uploadFile(Request $request){

        //validasi jam online
        $response = $this->notOnline();
        if($response != 0) return response()->json('Admin tidak Online', 500);

        //save hasil upload
        $file = $request['file'];
        $file->move('storage/upload/chats/', date('ymdhis') . '.' . $file->getClientOriginalExtension());

        //ambil data request
        $conversation_id = $request['conversation_id'];
        $filepath = 'storage/upload/chats/' . date('ymdhis') . '.' . $file->getClientOriginalExtension();
        $is_user = $request['is_user'];

        //insert chat baru
        $chat = new Chat;
        $chat->conversation_id = $conversation_id;
        $chat->sender_name = $request['sender_name'];
        $chat->is_new_user = !$is_user;
        $chat->is_new_owner = $is_user;
        $chat->chat = '';
        $chat->filepath = $filepath;
        $chat->is_owner = !$is_user;
        $chat->save();

        //update data conversation untuk user
        $this->updateConversation($chat->conversation, $is_user);

        return response()->json('success', 200);

    }

    //fungsi untuk set email
    public function retrieveEmailAndName(Request $request){

        //set data awal, bisa dipakai untuk yang belum login
        $response = array(
            'started_email' => session('started_email', ''),
            'sender_name' => session('sender_name', '')
        );

        //kalau sudah login, ambil data dari user
        if(auth()->check()){
            $usersetting = auth()->user()->usersetting;
            $response = array(
                'started_email' => $usersetting->email,
                'sender_name' => $usersetting->first_name
            );
        }

        return $response;

    }


    //fungsi untuk set session dari user yang tidak login (isi form)
    public function setSession(Request $request){

        $started_email = $request['started_email'];
        $sender_name = $request['sender_name'];

        session()->put('started_email', $started_email);
        session()->put('sender_name', $sender_name);

        $response = array(
            'started_email' => $started_email,
            'sender_name' => $sender_name
        );

        return $response;

    }


    //fungsi awal untuk ngambil conversation yang sedang aktif
    public function activeConversation(Request $request){

        //cek apakah ada conversation aktif
        //kalau tidak, buat baru
        $conversation = Conversation::where('started_email', 'like', $request['started_email'])
                ->where('is_end', '=', 0)
                ->select('id')->first();

        if($conversation){
            return $conversation->id;
        }

        return $this->newConversation($request['started_email']);

    }


    //fungsi untuk mengambil chats dari conversation yang sedang aktif
    public function initialConversation(Request $request){

        //ambil datanya
        $conversation_id = $request['conversation_id'];
        $is_user = $request['is_user'];

        //sudah dapat conversation_id nya, ambil chats nya
        $chats = Chat::where('conversation_id', '=', $conversation_id)
                ->select('id', 'conversation_id', 'sender_name', 'chat', 'filepath', 'is_new_user', 'is_new_owner', 'is_owner')
                ->orderBy('created_at')->get();

        $this->updateNewChats($chats, $is_user);

        return $chats;

    }


    //fungsi ini untuk mengirimkan chat ke conversation_id yang sudah ada
    public function sendChat(Request $request){

        //validasi jam online
        $response = $this->notOnline();
        if($response != 0) return $response;

        //ambil data request
        $conversation_id = $request['conversation_id'];
        $chats = $request['chats'];
        $is_user = $request['is_user'];


        //insert chat baru
        $chat = new Chat;
        $chat->conversation_id = $conversation_id;
        $chat->sender_name = $request['sender_name'];
        $chat->is_new_user = !$is_user;
        $chat->is_new_owner = $is_user;
        $chat->chat = $chats;
        $chat->filepath = "";
        $chat->is_owner = !$is_user;
        $chat->save();

        //update data conversation untuk user
        $this->updateConversation($chat->conversation, $is_user);

        $chat = Chat::where('sender_name', '=', $request['sender_name'])
                ->select('sender_name', 'chat', 'is_owner')
                ->orderBy('created_at', 'desc')->first();

        return $chat;

    }


    //fungsi untuk mengambil daftar user yang conversationnya masih aktif
    public function activeConversationList(Request $request){

        //cek apakah dia dari hasil search
        $search_keyword = $request['search_keyword'];
        if(strlen($request['search_keyword']) > 0){
            $conversations = Conversation::join('chats', 'chats.conversation_id', '=', 'conversations.id')
                ->where('chats.chat', 'like', '%' . $search_keyword . '%')
                ->where('conversations.is_end', '=', 0)
                ->groupBy('conversations.id')
                ->selectRaw('conversations.id, chats.sender_name, count(if(is_new_owner = 1, 1, NULL)) as count')
                ->orderBy('conversations.is_end', 'desc')
                ->get();
        }
        else{
            $conversations = Conversation::join('chats', 'chats.conversation_id', '=', 'conversations.id')
                ->where('conversations.is_end', '=', 0)
                ->groupBy('conversations.id')
                ->selectRaw('conversations.id, chats.sender_name, count(if(is_new_owner = 1, 1, NULL)) as count')
//                ->orderBy('chats.sender_name', 'asc')
                ->orderBy('conversations.is_end', 'desc')
                ->get();
        }


        return $conversations;

    }


    //fungsi untuk refresh chat yang baru
    public function refreshChat(Request $request){

        $conversation_id = $request['conversation_id'];

        //cek apakah conversation sudah end
        $conversation = Conversation::find($conversation_id);
        if($conversation->is_end){
            session()->flush();
            return $conversation->is_end;
        }

        $is_user = $request['is_user'];

        $chats = Chat::where('conversation_id', '=', $conversation_id);

        //kalau dia user, tampilin chat yg blm pernah dibaca user
        //kalau bukan, tampilin chat yang blm pernah dibaca ownerr
        if($is_user == 1){
            $chats = $chats->where('is_new_user', '=', 1);
        }
        else{
            $chats = $chats->where('is_new_owner', '=', 1);
        }

        $chats = $chats->select('id', 'sender_name', 'chat', 'filepath', 'is_new_user', 'is_new_owner', 'is_owner')
                ->get();

        //update chats jadi sudah tidak new
        $this->updateNewChats($chats, $is_user);

        return $chats;

    }

    //fungsi untuk end conversation
    public function endConversation(Request $request){

        $conversation_id = $request['conversation_id'];

        $conversation = Conversation::find($conversation_id);
        $conversation->is_end = 1;
        $conversation->save();

        //send conversationnya ke email owner
        $subject = "History Chat dengan ID : " . $request['conversation_id'];
        $email_message = "";
        foreach($conversation->chats as $chat){
            $email_message .= $chat->sender_name . " : " . $chat->chat . "\r\n";
        }

        $result = Mail::raw($email_message, function ($message) use ($subject) {
            $message->from('noreply@koreanluxury.com', 'Chat System');
            $message->to('fasikristophani@gmail.com', $name = null);
            $message->subject($subject);
        });

        return $result;
    }

    //fungsi untuk menampilkan hasil pencarian chat
    public function searchChat(Request $request){

        $search_keyword = $request['search'];

        if(strlen($search_keyword) > 0){
            return $search_keyword;
        }

        return "";

    }


    //fungsi untuk create conversation baru dan balikin id nya
    private function newConversation($email){
        $conversation = new Conversation;
        $conversation->started_email = $email;
        $conversation->is_end = 0;
        $conversation->is_user_reply = 0;
        $conversation->is_owner_reply = 0;
        $conversation->save();

        return $conversation->id;
    }

    //fungsi untuk update data conversation
    //kalau is_user_reply = 1 artinya user yang reply
    //kalau is_user_reply = 0 artinya owner yang reply
    private function updateConversation($conversation, $is_user_reply){
        $conversation->is_user_reply = $is_user_reply;
        $conversation->is_owner_reply = !$is_user_reply;
        $conversation->save();
    }

    //set semua chats yang is_new = 1, jadi 0
    //artiny chats tersebut sudah pernah diambil
    //kalau dia user, is_new_user yang diubah
    //kalau bukan user, is_new_owner yang diubah
    private function updateNewChats($chats, $is_user){
        foreach($chats as $chat){
            if($is_user){
                if($chat->is_new_user){
                    $chat->is_new_user = 0;
                    $chat->save();
                }
            }
            else{
                if($chat->is_new_owner){
                    $chat->is_new_owner = 0;
                    $chat->save();
                }
            }
        }
    }

    private function notOnline(){
        if($this->validateOnlineChat()){
            $sender_name = "Admin";
            $chats = "Hello.\r\n"
                    . "Ini adalah pusat layanan customer www.koreanluxury.com\r\n\r\n"
                    . "Kami buka\r\n"
                    . "hari senin-jumat\r\n"
                    . "Jam 10:00 - 17:00 WiB\r\n\r\n"
                    . "Mohon maaf diluar jam tersebut kami tidak bisa membalas pesan kamu.\r\n\r\n"
                    . "Urgent?\r\n"
                    . "Silahkan telepon kami di\r\n"
                    . "0896 8787 7775\r\n\r\n"
                    . "online:\r\n"
                    . "Senin-jumat\r\n"
                    . "Jam 9:30 - 17:00";
            $is_owner = 1;
            $response = array(
                'sender_name' => $sender_name,
                'chat' => $chats,
                'is_owner' => $is_owner
            );
            return $response;
        }
        else return 0;
    }

    //fungsi untuk validasi apakah jam online chat valid
    private function validateOnlineChat(){

        //validasi apakah weekday
        if(\Carbon\Carbon::now()->isWeekend()){
            return true;
        }

        //validasi apakah jam saat ini antara jam 10.00 - 17.00
        if((\Carbon\Carbon::now()->hour <= 9 && \Carbon\Carbon::now()->minute < 30)
                || \Carbon\Carbon::now()->hour > 17){
            return true;
        }

        return false;
    }


}
