<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Lot;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $order = Order::withCount('products')->with('user')->get();
        foreach ($order as $key ) {
            # code...
            $key->getStatusLabelAttribute();
        }

        return $this->returnSuccess(200, $order );
    }
    public function getOrdersTable(Request $request)
    {
        $order = Order::query()->with('user', 'client');

        if(!empty(request('filter_start_date'))){
            $order->whereBetween('created_at', [request('filter_start_date'), request('filter_end_date')]);
        }
        if(!empty(request('filter_tracker_id'))){
            $order->where('trancker', 'like', '%'.request('filter_tracker_id').'%');
        }

        if(!empty(request('order_sort_date')))   $order->orderBy('created_at', request('order_sort_date'));
        if(!empty(request('order_sort_status')))  $order->orderBy('status', request('order_sort_status'));

        return DataTables::of($order)->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLastByType(Request $request) {
        

        $order = Order::withCount('products')->with('user')->take(5)->where('status',$request->type)->get();
        foreach ($order as $key ) {
            # code...
            $key->getStatusLabelAttribute();
        }
        return $this->returnSuccess(200, $order );
    }
    public function getOrderById($id) {
        

        $order = Order::withCount('products')->with(['user', 'products.lotes', 'client'])->find($id);
        $order->getStatusLabelAttribute();
        return $this->returnSuccess(200, $order );
    }
    public function createOrder(Request $request)
    {
        $order = Order::create([
           'client_id'      => $request->client ?? $request->user()->id,
           'other_address'  => $request->address,
           'status'         => '1',
           'trancker'       => '00'.rand(10000, 99999),
           'created_by'     => $request->user()->id,
        ]);

        try{
            $this->addProductforOrder($order->id, json_decode($request->products,true));
            $this->decreaseStockInProduct(json_decode($request->products,true));
        }catch(Exception $e){
            return $this->returnFail(400, $e->getMessage());
        }

        return $this->returnSuccess(200, [

            json_decode($request->products,true)
        ]);

    }

    public function changeStatus(Request $request, $idOrder)
    {
        $order = Order::find($idOrder);

        if(!$order) return $this->returnFail(404, "Orden no encontrada.");

        if($order->status == $request->newStatus) return $this->returnFail(404, "Nuevo estado no valido.");

        $order->status = $request->newStatus;
        $order->save();

        return $this->returnSuccess(200, $order );
    }
    private function addProductforOrder($order, $products){
        foreach ($products as $key) {
            DB::table('products_x_orders')->insert([
                'order_id' => $order,
                'product_id' => $key['id'],
                'quantity'  => $key['quantity'],
                'lote_id'   => $key['selected_lote']['id_lote'],
            ]);
        }

    }
    private function decreaseStockInProduct($products){


        foreach ($products as $key) {
            
            $lote = Lot::find($key['selected_lote']['id_lote']);
            $lote->quantity = intval($lote->quantity) - intval($key['quantity']);
            $lote->save();
            
            $product = Product::find($key['id']);
            $product->stock = intval($product->stock) - intval($key['quantity']);
            
            $product->due_date_most_evenly = $lote->quantity == 0
                ? Lot::where('quantity','>','0')->where('product_id', $product->id)->first()->due_date
                : $product->due_date_most_evenly;

                $product->save();
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit($id)
    {
        //
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
        //
    }
}
