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
        $chats = Chat::query()->withCount('messagesUnread')->with(['receipet', 'sender','messages.sender','messagesUnread'])->orderBy('updated_at', 'DESC');

        if ($request->user()->id !== 1) {
            $chats = $chats->where('sender_id', $request->user()->id);
        }
        $chats->where('ticket_number', 'like', '%'.$request->chat_id.'%');

        if($request->onlyClosed == 'true')
        {
            $chats->where('status', '0');
            return  $this->returnSuccess(200, $chats->get());
        }
        if($request->show == 'false') $chats->where('status', '1');
        return  $this->returnSuccess(200, $chats->get());

    }
    public function getChatById(Request $request, $id){
        $chat = Chat::withCount('messagesUnread')->with(['receipet', 'sender', 'messages.sender','messagesUnread']);
        
        $this->readMessages($chat->find($id), $request->user()->id);
        return  $this->returnSuccess(200, $chat->find($id));
    }
    public function storeChat(Request $request){
        $newChat = Chat::create([
            'title' => $request->title, 
            'reference_id' => null,
            'sender_id' => $request->user()->id,
            'recept_id'  => 1,
            'type'   => $request->type,
            'status' => 1,
            'ticket_number'=> '00'.rand(10000, 99999),
        ]);
        
         ChatMessage::create([
            'message' => $request->message,
            'chat_id' => $newChat->id,
            'type_messages' => 'text',
            'sender_id' => $newChat->sender_id,
            'read' => 0,
        ]);

        RealTimeChatMessage::dispatch($newChat->sender_id);
        RealTimeChatMessage::dispatch($newChat->recept_id);

        return  $this->returnSuccess(200, $newChat);
    }
    public function changeStatus(Request $request, $id){
        $chat =Chat::find($id);
        $chat->status = $request->status;
        $chat->save();
        
        return  $this->returnSuccess(200, $chat);
    }
    public function deleteChat($id){
        if (!$id) {
            return $this->returnFail(400, "No se consigue el ID.");
        }

        $chat = Chat::find($id);


        if (!$chat) {
            return $this->returnFail(404, "Chat no encontrado.");
        }

        $chat->delete();

        return $this->returnSuccess(200, ['id' => $id, 'deleted_at' => $chat->deleted_at]);
    }
    public function newMessage($chatId, Request $request){
        
        $chat =Chat::find($chatId);
        $newMessage = ChatMessage::create([
            'message' => $request->message,
            'chat_id' => $chatId,
            'type_messages' => 'text',
            'sender_id' => $request->user()->id,
            'read' => 0,
        ]);
        // event(new RealTimeChatMessage);
        RealTimeChatMessage::dispatch($chat->sender_id);
        RealTimeChatMessage::dispatch($chat->recept_id);

        $chat->update_chat = $chat->update_chat + 1;
        $chat->save();

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
    private function readMessages($chat, $see_user){
        ChatMessage::query()->where('chat_id', $chat->id)->where('sender_id', '!=', $see_user)->where('read', 0)->update(['read' => 1]);
    }
}
