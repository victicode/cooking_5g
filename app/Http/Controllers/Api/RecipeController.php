<?php

namespace App\Http\Controllers\Api;

use App\Models\Recipe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

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

        $recipes = Recipe::withCount('products')->with('chef');
        
        if($request->user()->rol_id !== 1){
            $recipes->where('created_by', $request->user()->id );
        }

        return $this->returnSuccess(200, $recipes->paginate(15) );
    }
    public function getRecipesTable(){
        $recipes = Recipe::query()->with(['chef', 'products',]);


        return DataTables::of($recipes)->filter(function ($query) {
            // if (!empty(request('filter_name'))) {
            //   $query->where('name','like','%'.request('filter_name').'%');
            // }
          })->toJson();
    }

    public function getRecipeById($id){
        return $this->returnSuccess(200, Recipe::with(['chef', 'products',])->find($id));
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
