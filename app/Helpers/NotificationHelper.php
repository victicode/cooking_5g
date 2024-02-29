<?php
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

function createNotification($data){


   $messageNotification  = notificationMessage($data);
    $notification = Notification::create([
      'message'       =>  $messageNotification,
      'order_id'      =>  $data['order'] ?? null,
      'product_id'    =>  $data['product'] ?? null,
      'read'          =>  0,
      'type'          =>  $data['type'],

    ]);

    return $notification;
}

function notificationMessage($data){

  return $data['type'] == 1 
    ?  $data['name'] .' ha creado una nueva orden: '.'<a class="notify-hipervin">#'.$data['order'] . '</a>'
    :  'el producto "'.$data['title'].'" tiene poco stock';

}
function orderNumberFormat($id){

  return  substr('0000000',0, 7 - strlen(strval($id)) ) . strval($id) ;

}