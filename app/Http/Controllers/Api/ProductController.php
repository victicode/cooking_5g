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
    public function create()
    {
        //
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
    public function storeProduct(Request $request)
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
