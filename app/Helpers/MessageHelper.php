<?php


use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\MessageCooking;
use App\Http\Controllers\Controller;

function createMessage($data){



  return 'succes';
}
function seeAllMessage($user){
  Message::where('read', 0)->where('recept_id', $user)->update(['read' => 1]);
  return 'yes';
}
function getAllMessage($id){

  return [
   'all' => Message::take(10)->orderBy('created_at', 'DESC')->where('recept_id', $id)->with(['product', 'sender'])->get(),
   'new_count' => Message::where('read', 0)->orderBy('created_at', 'DESC')->where('recept_id', $id)->take(10)->count(),
  ];

}