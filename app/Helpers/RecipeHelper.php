<?php

use App\Events\NotificationCooking;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Order;

function allIngredientsRecipe($recipe){
  $all ='';
  $index = 0;
  foreach (json_decode($recipe->ingredients,true) as $key ) {
   $all .= trim($key['name']);
   $all .= ', ';
  }

  foreach ($recipe->cooking_ingredients as $key ) {
    $all = $all.'<b>'.trim($key->title);
    $index++;
    $all.= $index == count($recipe->cooking_ingredients) ? '.</b> ' :',</b> ' ;
    
   }
  return $all;

}
function totalPrint($data){
  $all = 0;

  foreach ($data as $key ) {
    $all = $all + intval($key->quantity);
  }
  return $all +1;

}