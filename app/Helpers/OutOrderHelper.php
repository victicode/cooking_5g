<?php

use App\Events\NotificationCooking;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Order;

function recipeOfProduct($id, $recipes){
  if($id){
    $selectedRecipe = null;
    foreach ($recipes as $key) {
      if($id == $key->id) $selectedRecipe = $key->title;
    }
    return $selectedRecipe;
  }
  return'';
    

}
