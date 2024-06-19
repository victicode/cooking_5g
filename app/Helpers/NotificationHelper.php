<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Events\NotificationCooking;
use App\Http\Controllers\Controller;

function createNotification($data){
  switch ($data['type']) {
    case 1:
      createOrderNotifiaction($data);
      break;
    case 2:
      changeStatusOrderNotifiaction($data);
      break;
    case 3:
      productStockNotifiaction($data);
      break;
  }
  event(new NotificationCooking);

  return 'succes';
}
function seeAllNotifiaction($user,$type){
  Notification::where('read', 0)->where('user_id', $user)->where('type', $type)->update(['read' => 1]);
  return 'yes';
}
function getAllNotifiaction($id){

  return [
   'all' => Notification::take(10)->orderBy('created_at', 'DESC')->where('user_id', $id)->get(),
   'new_count' => Notification::where('read', 0)->orderBy('created_at', 'DESC')->where('user_id', $id)->take(10)->count(),
  ];

}
function notificationMessage($data, $type){
  $item = $type !== 6 
  ? Order::find($data['order'])
  : Product::find($data['product']);


  switch ($type) {
    case 1:
      return $item->client->name.' ha creado una nueva orden: '.'<a class="notify-hipervin">#'.orderNumberFormat($item->id) . '</a>';
      break;
    case 2:
      return 'Has creado la orden: '.'<a class="notify-hipervin">#'.orderNumberFormat($item->id) . '</a> exitosamente';
      break;
    case 3:
      return  ' La orden: <a class="notify-hipervin">#'.orderNumberFormat($item->id) . '</a> ha sido despachada.';
      break;
    case 4:
      return  ' La orden: <a class="notify-hipervin">#'.orderNumberFormat($item->id) . '</a> ha sido recepcionada.';
      break;
    case 5:
      return  ' La orden: <a class="notify-hipervin">#'.orderNumberFormat($item->id) . '</a> ha sido cancelada.';
      break;
    case 6:
      return 'El producto <a class="notify-hipervin">"'.$item->title.'"</a> tiene poco stock';
      break;
  }


}
function changeStatusOrderNotifiaction($data){
  $order = Order::find($data['order']);
  $messageId = $order->status + 1;
  if($order->status == 0)$messageId = 5;

  Notification::create([
    'message'       =>  notificationMessage($data, $messageId),
    'order_id'      =>  $data['order'],
    'product_id'    =>  null,
    'user_id'       =>  Order::find($data['order'])->client_id,
    'read'          =>  0,
    'type'          =>  1,

  ]);
  Notification::create([
    'message'       =>  notificationMessage($data, $messageId),
    'order_id'      =>  $data['order'],
    'product_id'    =>  null,
    'user_id'       =>  1,
    'read'          =>  0,
    'type'          =>  1,

  ]);
  return;
}
function createOrderNotifiaction($data){
    Notification::create([
      'message'       =>  notificationMessage($data, 2),
      'order_id'      =>  $data['order'],
      'product_id'    =>  null,
      'user_id'       =>  Order::find($data['order'])->client_id,
      'read'          =>  0,
      'type'          =>  1,

    ]);

    Notification::create([
      'message'       =>  notificationMessage($data, 1),
      'order_id'      =>  $data['order'],
      'product_id'    =>  null,
      'user_id'       =>  1,
      'read'          =>  0,
      'type'          =>  1,

    ]);
}
function productStockNotifiaction($data){
  Notification::create([
    'message'       =>  notificationMessage($data, 6),
    'order_id'      =>  null,
    'product_id'    =>  $data['product'],
    'user_id'       =>  1,
    'read'          =>  0,
    'type'          =>  2,

  ]);
}
function orderNumberFormat($id){
  return  substr('0000000',0, 7 - strlen(strval($id)) ) . strval($id) ;
}