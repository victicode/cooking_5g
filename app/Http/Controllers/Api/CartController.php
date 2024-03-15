<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Exception;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $cart =Cart::where('user_id',$request->user()->id,)->first();
        if(!$cart){
            return $this->returnFail(400, 'Carrito no encontrado');

        }

       
        return $this->returnSuccess(200, $cart );
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $currentCart = Cart::where('user_id',$request->user()->id,)->first();

        if(!$currentCart){
            $car = Cart::create([
                'user_id' => $request->user()->id,
                'description' => $request->products.'?',
            ]);
            return $this->returnSuccess(200, [$car]);
        }
        $currentCart->description .= $request->products.'?';
        $currentCart->save();
        return $this->returnSuccess(200, $currentCart->description);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editItemQuantity(Request $request, $id)
    {
        $currentCart =Cart::where('user_id',$request->user()->id,)->first();
        $itemsOfCart = explode("?", $currentCart->description);
        $itemsOfCart[$id] = $request->products;

        $currentCart->description = implode("?", $itemsOfCart);
        $currentCart->save();
        return $this->returnSuccess(200, $request->products);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $currentCart =Cart::find($id);
        $currentCart->description = null;
        $currentCart->save();
        return $this->returnSuccess(200, 'success');
    }
    public function deleteItem(Request $request,$id){
        $currentCart =Cart::where('user_id',$request->user()->id,)->first();
        $itemsOfCart = explode("?", $currentCart->description);
        unset($itemsOfCart[$id]);

        $currentCart->description = implode("?", $itemsOfCart);
        $currentCart->save();
        return $this->returnSuccess(200, $currentCart->description);
    }
}
