<?php


use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\MessageCooking;
use App\Http\Controllers\Controller;

function createMessage($data){



  return 'succes';
}
function seeAllMessage(){
  Message::where('read', 0)->update(['read' => 1]);
  return 'yes';
}
function getAllMessage(){

  return [
   'all' => Message::take(10)->orderBy('created_at', 'DESC')->with(['products', 'sender'])->get(),
   'new_count' => Message::where('read', 0)->orderBy('created_at', 'DESC')->take(10)->count(),
  ];

}