<?php

namespace App\Http\Controllers\Api;

use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use App\Events\RealTimeChatMessage;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    //withCount('messagesUnread')
    public function getChats(Request $request){

        return $request->user()->id == 1
        ? $this->returnSuccess(200, Chat::withCount('messagesUnread')->with(['receipet', 'sender','messages'])->get())
        : $this->returnSuccess(200, Chat::withCount('messagesUnread')->with(['receipet', 'sender','messages'])->where('sender_id', $request->user()->id)->get());
    }
    public function getChatById($id){

        return  $this->returnSuccess(200, Chat::withCount('messagesUnread')->with(['receipet', 'sender', 'messages'])->find($id));
    }
    public function newMessage($chatId, Request $request){

        // $chat = Chat::with(['receipet', 'sender', 'messages'])->find($chatId);
        $newMessage = ChatMessage::create([
            'message' => $request->message,
            'chat_id' => $chatId,
            'type_messages' => 'text',
            'sender_id' => $request->user()->id,
            'read' => 0,
        ]);
        event(new RealTimeChatMessage);
        return  $this->returnSuccess(200, $newMessage);
    }
    public function allUnReadMessages( Request $request){

        $chats = Chat::withCount('messagesUnread')->where('sender_id', $request->user()->id)->get();
        if($request->user()->id == 1){
            $chats = Chat::withCount('messagesUnread')->get();
        }
        $all = 0;
        foreach ($chats as $key) {
            $all = $all + $key->messages_unread_count;
        }
        return  $this->returnSuccess(200,  $all);
    }
    public function typingFuction($id){

        // RealTimeChatMessage::dispatch(
        //     Chat::withCount('messagesUnread')->with(['receipet', 'sender','messages'])->find($id)
        // );
        return  $this->returnSuccess(200, Chat::withCount('messagesUnread')->with(['receipet', 'sender','messages'])->find($id));
    }

}
