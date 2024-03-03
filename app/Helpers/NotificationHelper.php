<?php

use App\Events\NotificationCooking;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Order;

function createNotification($data){


   $messageNotification  = notificationMessage($data);
    $notification = Notification::create([
      'message'       =>  $messageNotification,
      'order_id'      =>  $data['order'] ?? null,
      'product_id'    =>  $data['product'] ?? null,
      'read'          =>  0,
      'type'          =>  $data['type'],

    ]);
    event(new NotificationCooking);
    return $notification;
}
function seeAllNotifiaction($type){
  Notification::where('read', 0)->where('type', $type)->update(['read' => 1]);
  return 'yes';
}
function getAllNotifiaction(){

  return [
   'all' => Notification::take(10)->orderBy('created_at', 'DESC')->get(),
   'new_count' => Notification::where('read', 0)->orderBy('created_at', 'DESC')->take(10)->count(),
  ];

}
function notificationMessage($data){
  $item = $data['type'] !== 2 
  ? Order::find($data['order'])
  : Product::find($data['product']);

  if($data['type'] == 3 ){
    return  ' La orden: <a class="notify-hipervin">#'.orderNumberFormat($item->id) . '</a> ha sido depachada.';
  }

  return $data['type'] == 1 
  ? $item->client->name.' ha creado una nueva orden: '.'<a class="notify-hipervin">#'.orderNumberFormat($item->id) . '</a>'
  :  'El producto <a class="notify-hipervin">"'.$item->title.'"</a> tiene poco stock';

}
function orderNumberFormat($id){

  return  substr('0000000',0, 7 - strlen(strval($id)) ) . strval($id) ;

}