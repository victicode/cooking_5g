<?php


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
