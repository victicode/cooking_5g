<?php

namespace App\Http\Controllers\Api;

use App\Models\Chat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    //
    public function getChats(){

        return  $this->returnSuccess(200, Chat::with(['receipet', 'sender',])->get());
    }
    public function getChatById($id){

        return  $this->returnSuccess(200, Chat::with(['receipet', 'sender', 'messages'])->find($id));
    }
}
