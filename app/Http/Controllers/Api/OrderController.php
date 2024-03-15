<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Lot;
use App\Models\Order;
use App\Models\Product;
use App\Models\OutOrder;
use Barryvdh\DomPDF\Facade\Pdf;
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
        
        try {
            //code...
            $order = Order::withCount('products')->with(['user', 'products.lotes', 'client', 'outOrder'])->find($id);
            // $order->getStatusLabelAttribute();
        } catch (Exception $th) {
            return $this->returnFail(400, $th->getMessage());
        }
        return $this->returnSuccess(200, $order );
    }
    public function createOrder(Request $request)
    {
        $order = Order::create([
           'client_id'      => $request->client ?? $request->user()->id,
           'other_address'  => $request->address ?? $request->user()->user_address,
           'status'         =>  '1',
           'trancker'       => '00'.rand(10000, 99999),
           'created_by'     => $request->user ?? $request->user()->id,
        ]);
        $this->addProductforOrder($order->id, json_decode($request->products,true), 'order');


        $this->newNotification(['order'=>$order->id, 'type' => 1]);     

        
        if($request->isManual == 'true'){
    
            $requestOutOrder = new Request([
                'order'   => $order->id,
                'user'       => $request->user()->id,
                'products'   => $this->formatedRequestProductsByOrder(json_decode($request->products,true))
            ]);
        
            $this->createOutOrder($requestOutOrder);
        }
        
        return $this->returnSuccess(200,  $order);

    }
    public function createOutOrder(Request $request)
    {
        $out_order = OutOrder::create([
           'order_id'      => $request->order,
           'created_by'  => $request->user ?? $request->user()->id,
        ]);

        try{

            $this->addProductforOrder($out_order->id,json_decode( $request->products, true), 'out_order');
            $this->decreaseStockInProduct(json_decode($request->products,true));
        }catch(Exception $e){
            return $this->returnFail(400, $e->getMessage());
        }


       
        $newStatus= new Request([
            'newStatus'   => '2',
        ]);

        $this->changeStatus($newStatus, $request->order);
        
        return json_decode( $request->products, true);

    }
    public function printOutOrder(Request $request, $id)
    {
        $order = OutOrder::with('products', 'order.client')->where('id',$id)->first();

        if($request->user()->rol_id == 1 ||$request->user()->id == $order->created_by){

            $order = OutOrder::with('products', 'order.client')->where('id',$id)->first();
    
            if(!$order) return $this->returnFail(400, 'algo ha salido mal');
    
            $pdf = Pdf::loadView('outOrderTemplate2', [ 'order' => $order]);

            return $pdf->stream('Salida ');
        }
    }
    public function changeStatus(Request $request, $idOrder)
    {
        $order = Order::find($idOrder);

        if(!$order) return $this->returnFail(404, "Orden no encontrada.");

        if($order->status == $request->newStatus) return $this->returnFail(404, "Nuevo estado no valido.");

        $order->status = $request->newStatus;
        $order->save();

        $this->newNotification(['order'=>$order->id, 'type' => 3]);   
        return [$order,$request->newStatus] ;
    }
    private function addProductforOrder($order, $products, $type){

        if ($type == 'order') {
            
            foreach ($products as $key) {
                DB::table('products_x_orders')->insert([
                    'order_id' => $order,
                    'product_id' => $key['id'],
                    'quantity'  => $key['quantity'],
                ]);
            }
            return ;
        }

        foreach ($products as $product) {
            foreach ($product as $lotesh) {
                DB::table('products_x_out_order')->insert([
                    'out_order_id' => $order,
                    'product_id' => $lotesh['selected_lote']['product_id'],
                    'quantity'  => 1,
                    'lote_id'   => $lotesh['selected_lote']['id_lote'],
                ]);
             }
        }

        

    }
    private function decreaseStockInProduct($products){


        foreach ($products as $product) {
            foreach ($product as $lotes) {
                $lote = Lot::find($lotes['selected_lote']['id_lote']);
                $lote->quantity = intval($lote->quantity) - intval($lotes['quantity']);
                $lote->save();
            
               
            }
        }

    }
    private function formatedRequestProductsByOrder($products){
        $newProducts = [];
        $i=0;
        $casualidade = [];
        foreach ($products as  $loteOfProduct) {
            $datos = $loteOfProduct['selected_lote']['product_id'];
            $casuali = array_search($datos, $casualidade);

            if(!$casuali){
                $casualidade[$i] = $loteOfProduct['selected_lote']['product_id'];
                $newProducts[$i]  = [$loteOfProduct]; 

            }else{
                array_push($newProducts[$casuali],$loteOfProduct);
            }
            $i++;
        }

        return json_encode($newProducts) ;
    }
    public function newNotification($data){

        $notificationData=[
            'order' => $data['order'],
            'type'  => $data['type'] ,
        ];
        $notification = createNotification($notificationData);
        
        return  $notification;
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
