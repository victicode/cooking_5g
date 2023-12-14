<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use Exception;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->returnSuccess(200, Product::with(['dismantling.products_pieces'])->get());
    }
    public function getProductsTable(Request $request){
        $products = Product::query();

        return DataTables::of($products)->filter(function ($query) {
            if (!empty(request('filter_product_title'))) {
              $query->where('title','like','%'.request('filter_product_title').'%');
            }
          })->toJson();
  
    }
    public function getProductsCriticalStock()
    {
        return $this->returnSuccess(200, Product::orderBy('stock', 'asc')->where('stock', '<', '20')->take(5)->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeProduct(Request $request)
    {
        $imgPath = '';
        if ($request->img) {
            $imgPath = 'images/product/' . trim(str_replace(' ', '_', $request->title ));
            $request->file('img')->move(public_path() . '/images/product/', $imgPath);
        }

        // $validated = $this->validateRequiredFields($request->all());

        // if (!$validated['validated']) {
        //     return $this->returnFail(400, $validated['message']);
        // }



            $product = Product::create([
                'title'             => $request->title,
                'description'       => $request->description,
                'short_description' => $request->short_description,
                'stock'             => $request->initial_stock,
                'type_of_unit'      => $request->type_unit,
                'img'               => $imgPath,
                'is_dismantling'    => 1,
                'created_by'        => 1,
                'updated_by'        => 1
        
                ]);

        

        return $this->returnSuccess(200, $product );

    }
    public function getProductById($id){
        return $this->returnSuccess(200, Product::with(['dismantling.products_pieces'])->find($id));
    }
    public function getProductBySearch(Request $request){
        $products = Product::query()->with(['dismantling.products_pieces']);

        if(!empty($request->title)){
            $products->where('title', 'like', '%'.$request->title.'%');
        }

        return $this->returnSuccess(200, $products->take(10)->get());
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

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
    private function validateRequiredFields($inputRequest)
    {

 

        if (!array_key_exists('title', $inputRequest) && empty($inputRequest['title']) )  {
            return [
                'validated' => false,
                'message' => 'El titulo del producto es requerido.',
            ];
        }
        if (!array_key_exists('description', $inputRequest) && empty($inputRequest['description']) ) {
            return [
                'validated' => false,
                'message' => 'La descripción del producto es requerida.',
            ];
        }
        if (!array_key_exists('short_description', $inputRequest) && empty($inputRequest['short_description']) ){
            return [
                'validated' => false,
                'message' => 'La descripción corta es requerida.',
            ];
        }
        if ( !array_key_exists('initial_stock', $inputRequest) && empty($inputRequest['initial_stock']) ){ 
            return [
                'validated' => false,
                'message' => 'El stock inicial es requerido.',
            ];
        }
        if (!array_key_exists('type_unit', $inputRequest) && empty($inputRequest['type_unit'])) {
            return [
                'validated' => false,
                'message' => 'El tipo de unidad es requerido.',
            ];
        }
        if (!array_key_exists('is_dismantling', $inputRequest) ){
            return [
                'validated' => false,
                'message' => 'Se requiere que especifique si trae despecies.',
            ];
        }

        return [
            'validated' => true,
        ];
    }
}
