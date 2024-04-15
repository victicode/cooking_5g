<?php

namespace App\Http\Controllers\Api;

use App\Models\Recipe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //

        $recipes = Recipe::withCount('cooking_ingredients')->with('cooking_ingredients')
                    ->where('title', 'like', '%'.$request->recipe_title.'%');
        
        // if($request->user()->rol_id !== 1){
        //     $recipes->where('created_by', $request->user()->id );
        // }

        return $this->returnSuccess(200, $recipes->paginate(10) );
    }
    public function getRecipeById($id){
        return $this->returnSuccess(200, Recipe::with(['cooking_ingredients.dismantling.products_pieces', 'cooking_ingredients.lotes' ])->find($id));
    }
    public function getRecipeBySearch(Request $request){
        $products = Recipe::query()->withCount('cooking_ingredients')->with('cooking_ingredients');

        if(!empty($request->title)){
            $products->where('title', 'like', '%'.$request->title.'%');
        }

        return $this->returnSuccess(200, $products->take(10)->get());
    }
    public function storeRecipe(Request $request)
    {
        //
        $validated = $this->validateFieldsFromInput($request->all()) ;
        if (count($validated) > 0) return $this->returnFail(400, $validated[0]);

        if (!$request->File('image_url')) {
            return $this->returnFail(400, "La imagen es requerida.");
        }
        

        $imgPath = '';
        if ($request->image_url) {
            $imgPath = 'images/recipe/' . trim(str_replace(' ', '_', $request->title )).'.'.$request->File('image_url')->extension();
            $request->file('image_url')->move(public_path() . '/images/recipe/', $imgPath);
        }
        $videoUrl = '';
        if ($request->File('video_url')) {
            if ($request->video_url) {
                $videoUrl = 'images/recipe/video/' . trim(str_replace(' ', '_', $request->title )).'.'.$request->File('video_url')->extension();
                $request->file('video_url')->move(public_path() . '/images/recipe/video/', $videoUrl);
            }
        }

        try {
            $newRecipe = Recipe::create([
                'title'         =>  $request->title,
                'description'   =>  $request->description,
                'preparation'   =>  $request->preparation,
                'person_count'  =>  $request->person_count,
                'type'          =>  $request->type,
                'total_time'    =>  $request->total_time,
                'ingredients'   =>  $request->ingredients,
                'image_url'     =>  $imgPath,
                'video_url'     =>  $videoUrl == '' ? null : $videoUrl,
                'created_by'    =>  1,
    
            ]);
        } catch (Exception $th) {
            return $th->getMessage();
        }
        
        try {
            $this->addProductforRecipe ($newRecipe->id, json_decode($request->cooking_ingredients,true) );
        } catch (Exception $th) {
             return [
                'message' => $th->getMessage(),
                'ingredients_c'  =>   json_decode($request->cooking_ingredients,true)

             ];
        }
        

        return $this->returnSuccess(200, $newRecipe);
    }
    public function updateRecipe(Request $request, $recipeId){
        //
        $recipe = Recipe::find($recipeId);
        if(!$recipe) return $this->returnFail(400, 'Receta no encontrado');
        $imgPath = $recipe->image_url;
        if ($request->hasFile('image_url')) {
            $imgPath = 'images/product/' . trim(str_replace(' ', '_', $request->title )).'.'.$request->File('image_url')->extension();
            $request->file('image_url')->move(public_path() . '/images/product/', $imgPath);
        }
        try {
            $recipe->title         =  $request->title;
            $recipe->description   =  $request->description;
            $recipe->preparation   =  $request->preparation;
            $recipe->person_count  =  $request->person_count;
            $recipe->type          =  $request->type;
            $recipe->total_time    =  $request->total_time;
            $recipe->ingredients   =  $request->ingredients;
            $recipe->image_url     =  $imgPath;
            $recipe->video_url     =  null;
            $recipe->created_by    =  1; 


            $recipe->save();
        } catch (Exception $th) {
            return $th->getMessage();
        }
        try {
            $this->addProductforRecipe ($recipe->id, json_decode($request->cooking_ingredients,true) );
        } catch (Exception $th) {
            return $this->returnFail(400, ['message'=>$th->getMessage(),  'data'=> json_decode($request->cooking_ingredients,true)]  ) ;
        }
        
        return $this->returnSuccess(200,['data'=>$recipe,  'ongredientes' => json_decode($request->cooking_ingredients,true)]);


    }
    public function deleteRecipe($recipeId){
        if (!$recipeId) {
            return $this->returnFail(400, "El identificador del usuario.");
        }

        $recipe = Recipe::find($recipeId);


        if (!$recipe) {
            return $this->returnFail(404, "Usuario no encontrado.");
        }

        $recipe->delete();


        return $this->returnSuccess(200, ['id' => $recipeId, 'deleted_at' => $recipe->deleted_at]);
    }
    public function printRecipeTag(Request $request, $id=''){

        if($request->path() == 'api/recipes/client/print/multiple'){

            // return json_decode($request->recipes);
            return view('tagMultipleRecipeTemplate', [ 'recipes' => json_decode($request->recipes)]);
        }
        $recipe = Recipe::with(['cooking_ingredients.dismantling.products_pieces', 'cooking_ingredients.lotes' ])->find($id);
    
        if(!$recipe) return $this->returnFail(400, 'algo ha salido mal');
        
        return view('tagRecipeFormat', [ 'recipe' => $recipe, 'data' => [
            'imp'    => $request->quantity,
            'create' => $request->created,
            'consumo'=> $request->consumo,
        ] ]);
    }
    private function validateFieldsFromInput($inputs, $type = 'new'){

        $rules=[
            'title'                 => ['required', 'regex:/^[^$%&|<>#]*$/'],
            'description'           => ['required', 'regex:/^[^$%&|<>#]*$/'],
            'preparation'           => ['required', 'regex:/^[^$%&|<>#]*$/'],
            'person_count'          => ['required', 'integer'],
            'type'                  => ['required', 'regex:/^[^$%&|<>#]*$/'],
            'total_time'            => ['required', 'regex:/^[^$%&|<>#]*$/'],
            'ingredients'           => ['required', 'regex:/^[^$%&|<>#]*$/' ],
            'cooking_ingredients'   => ['required', 'regex:/^[^$%&|<>#]*$/' ],
            'image_url'             => ['required',File::types(['png', 'jpg', 'webp'])],
        ];
        $messages = [
            'title.required'                => 'El titulo es requerido.',
            'title.regex'                   => 'titulo no valido',
            'description.required'          => 'La descripción es requerido.',
            'description.regex'             => 'Descripción no valida',
            'preparation.required'          => 'La preparación es requerido.',
            'preparation.regex'             => 'Preparación no valido',
            'person_count.required'         => 'El número de personas es requerido.',
            'person_count.integer'          => 'Número de personas no valido',
            'type.required'                 => 'El tipo de plato es requerido.',
            'type.regex'                    => 'Tipo de plato no valido',
            'total_time.required'           => 'El tiempo total es requerido.',
            'total_time.regex'              => 'tiempo total no valido',
            'ingredients.required'          => 'Los ingredientes son requerido.',
            'ingredients.regex'             => 'ingredientes no valido',
            'cooking_ingredients.required'  => 'Los ingredientes son requerido.',
            'cooking_ingredients.regex'     => 'ingredientes no valido',
            'image_url.required'            => 'El archivo es requerido.',
            'image_url.file'                => 'Archivo no valido',
        ];


        $validator = Validator::make($inputs, $rules, $messages)->errors();

        return $validator->all() ;

        


    }
    private function addProductforRecipe($recipeId, $products){

        try {
            $recipe=DB::table('products_x_recipes')->where('recipe_id', $recipeId)->delete();

        } catch (\Throwable $th) {

        }


        foreach ($products as $key) {
            DB::table('products_x_recipes')->insert([
                'recipe_id'     => $recipeId,
                'product_id'    => $key['id'],
                'quantity'      => $key['pivot']['quantity'] ?? $key['quantity']  ,
            ]);
        }
        return ;
        
    }
}
