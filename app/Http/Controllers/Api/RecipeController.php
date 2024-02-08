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

        $recipes = Recipe::withCount('cooking_ingredients')->with('cooking_ingredients');
        
        if($request->user()->rol_id !== 1){
            $recipes->where('created_by', $request->user()->id );
        }

        return $this->returnSuccess(200, $recipes->paginate(15) );
    }
    public function getRecipesTable(){
        $recipes = Recipe::query()->with(['chef', 'cooking_ingredients',]);


        return DataTables::of($recipes)->filter(function ($query) {
            // if (!empty(request('filter_name'))) {
            //   $query->where('name','like','%'.request('filter_name').'%');
            // }
          })->toJson();
    }

    public function getRecipeById($id){
        return $this->returnSuccess(200, Recipe::with(['chef', 'cooking_ingredients',])->find($id));
    }

    public function storeRecipe(Request $request)
    {
        //
        $validated = $this->validateFieldsFromInput($request->all()) ;
        if (count($validated) > 0) return $this->returnFail(400, $validated[0]);

        if (!$request->File('image_url')) {
            return $this->returnFail(400, "El vaucher es requerido.");
        }


        $imgPath = '';
        if ($request->image_url) {
            $imgPath = 'images/product/' . trim(str_replace(' ', '_', $request->title )).'.'.$request->File('image_url')->extension();
            $request->file('image_url')->move(public_path() . '/images/product/', $imgPath);
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
                'video_url'     =>  null,
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
        

        return [
           'id'             => $newRecipe,
           'ingredients_c'  =>   json_decode($request->cooking_ingredients,true)
        ];
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
    private function addProductforRecipe($recipe, $products,){

        foreach ($products as $key) {
            DB::table('products_x_recipes')->insert([
                'recipe_id'     => $recipe,
                'product_id'    => $key['id'],
                'quantity'      => $key['quantity'],
            ]);
        }
        return ;
        
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
