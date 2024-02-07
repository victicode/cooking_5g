<?php

namespace App\Http\Controllers\Api;

use stdClass;
use Exception;
use App\Models\Lot;
use App\Models\Product;
use App\Models\Dismantling;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use PhpParser\Node\Expr\FuncCall;
use App\Http\Controllers\Controller;
use Illuminate\Database\Query\Builder;
use Yajra\DataTables\CollectionDataTable;

class ProductController extends Controller
{
    public function index()
    {
        return $this->returnSuccess(200, Product::with(['dismantling.products_pieces', 'lotes:due_date'])->get());
    }

    public function getProductById($id){
        return $this->returnSuccess(200, Product::with(['dismantling.products_pieces','lotes'])->find($id));
    }

    public function storeProduct(Request $request)
    {
        $imgPath = '';
        if ($request->img) {
            $imgPath = 'images/product/' . trim(str_replace(' ', '_', $request->title ));
            $request->file('img')->move(public_path() . '/images/product/', $imgPath);
        }

        $validated = $this->validateRequiredFields($request->all());

        if (!$validated['validated']) return $this->returnFail(400, $validated['message']);
        
        try {
            $product = Product::create([
                'title'             => $request->title,
                'description'       => $request->description,
                'short_description' => $request->short_description,
                'stock'             => $request->initial_stock,
                'type_of_unit'      => $request->type_unit,
                'img'               => $imgPath,
                'is_dismantling'    => $request->is_dismantling,
                'created_by'        => $request->user()->id,
                'updated_by'        => $request->user()->id
            ]);
        } catch (Exception $th) {
            return $this->returnSuccess(400, $th->getMessage() );
        }
    
        if($request->is_dismantling){
             $this->addDismantling($product->id, $request->dismantling);
        }

        $formatLote = $this->formatInputLot($request);
        $lote = $this->addLote($product->id, $formatLote);
        

        return $this->returnSuccess(200, $product);

    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::find($id);

        if(!$product) return $this->returnFail(400, 'Producto no encontrado');

        $imgPath = $product->img;
        if ($request->img !== 'undefined') {
            $imgPath = 'images/product/' . trim(str_replace(' ', '_', $request->title )).'_2';
            $request->file('img')->move(public_path() . '/images/product/', $imgPath);
        }

        $validated = $this->validateRequiredFields($request->all(), 'update');

        if (!$validated['validated']) return $this->returnFail(400, $validated['message']);
        
        try {
            $product->title             = $request->title;
            $product->description       = $request->description;
            $product->short_description = $request->short_description;
            $product->type_of_unit      = $request->type_unit;
            $product->img               = $imgPath;
            $product->is_dismantling    = $request->is_dismantling;
            $product->updated_by        = $request->user()->id;


            if($request->is_dismantling){
                $this->returnSuccess(200, [$this->addDismantling($product->id, $request->dismantling), json_decode($request->dismantling,true)] );
           }

            $product->save();
            
        } catch (Exception $th) {
            return $this->returnSuccess(400, $th->getMessage());
        }
        
        return $this->returnSuccess(200, Product::with(['dismantling.products_pieces'])->find($product->id));
    }

    public function addStock(Request $request, $id)
    {
        $product = Product::find($id);
        if(!$product) return $this->returnFail(400, 'Producto no encontrado');

        if($request->type == 1) $this->addLote($id, $request);
        if($request->type == 2) $this->reduceLote($id, $request);

        return $this->returnSuccess(200, [$product]);
    }

    public function getProductBySearch(Request $request){
        $products = Product::query()->with(['dismantling.products_pieces','lotes']);

        if(!empty($request->title)){
            $products->where('title', 'like', '%'.$request->title.'%');
        }

        return $this->returnSuccess(200, $products->take(10)->get());
    }   
    public function deleteProduct($productId)
    {
        if (!$productId) {
            return $this->returnFail(400, "El identificador del usuario.");
        }

        $product = Product::find($productId);
        $dismantlingsOfProducts= Dismantling::where('piece_product_id', $productId)->delete();


        if (!$product) {
            return $this->returnFail(404, "Usuario no encontrado.");
        }

        $product->delete();


        return $this->returnSuccess(200, ['id' => $productId, 'deleted_at' => $product->deleted_at]);
    }
    public function getlotesOfProductsTable(Request $request){
        $products = Lot::query()->with(['product.dismantling.products_pieces'])
        ->where('quantity', '>', 0)->join('products', 'products.id', '=', 'lotes.product_id');

        
        if(!empty(request('order_title')))  $products->orderBy('products.title', request('order_title'));
        
        
        if(!empty(request('order_due_date')))  $products->orderBy('due_date', request('order_due_date'));

        if(!empty(request('order_stock')))  $products->orderBy('quantity', request('order_stock'));
        
        if (!empty(request('filter_product_title'))) {
            $products->where('products.title','like','%'.request('filter_product_title').'%');
        }
    
        if($request->user()->rol_id !== 1){
            $products->where('created_by', $request->user()->id );
        }
        return DataTables::of($products)->toJson();
  
    }
    public function getProductsCriticalStock(Request $request)
    {

        $products = Lot::query()->with(['product.dismantling.products_pieces'])
        ->where('quantity', '<', '20')->join('products', 'products.id', '=', 'lotes.product_id')
        ->orderBy('quantity', 'asc')->take(5);

        if($request->user()->rol_id !== 1){
            $products->where('created_by', $request->user()->id );
        }
        return $this->returnSuccess(200, $products->get());
    }
    public function getLastLoteFromProduct($productId){
       return Lot::withTrashed()->where('product_id', $productId)->count();
    }
    private function validateRequiredFields($inputRequest, $type = "create" )
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
        if ( !array_key_exists('initial_stock', $inputRequest) && empty($inputRequest['initial_stock']) && $type == "create"){ 
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
    private function addDismantling($productId, $dismantlingsProducts){
        $dismantlingsProductsArrayFormat = json_decode($dismantlingsProducts,true); 
        Dismantling::where('product_id', $productId)->forceDelete();

        if( $dismantlingsProductsArrayFormat !== null ){
            foreach ($dismantlingsProductsArrayFormat as $dismantlingProduct) {
    
                Dismantling::create([
                    'product_id'        => $productId,
                    'piece_product_id'  => $dismantlingProduct['piece_product_id'],
                    'quantity'          => $dismantlingProduct['quantity'],
                ]);
            }
        }
    } 
    private function addLote($productId, $data){
        return Lot::create([
            'lote_code'  => $data->lote,
            'product_id' => $productId,
            'quantity'   => $data->quantity,
            'due_date'   => $data->due_date ,
        ]);
    } 
    private function reduceLote($productId, $data){

        $lote = Lot::where('id_lote', $data->lote)->where('product_id', $productId)->first();

        $lote->quantity = $lote->quantity - $data->quantity;
        $lote->save();
        return $lote;
    } 
    private function formatInputLot($inputs){
        $formatInputs = new stdClass(); 
        $formatInputs->lote = $inputs->initial_lote;
        $formatInputs->quantity = $inputs->initial_stock;
        $formatInputs->due_date = $inputs->due_date;

        return $formatInputs;

    }
}
