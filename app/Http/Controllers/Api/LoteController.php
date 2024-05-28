<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lot;

class LoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    public function getLoteById($id){
        return $this->returnSuccess(200, Lot::with(['product.dismantling.products_pieces','product.lotes'])->where('id_lote', $id)->first());
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteLote($loteId)
    {
        if (!$loteId) {
            return $this->returnFail(400, "ID no enviado.");
        }

        $lote = Lot::where('id_lote', $loteId)->first();

        // $dismantlingsOfProducts= Dismantling::where('piece_product_id', $lot)->delete();


        if (!$lote) {
            return $this->returnFail(404, "Lote no encontrado.");
        }
 
        $lote->delete();


        return $this->returnSuccess(200, ['id' => $loteId, 'deleted_at' => $lote->deleted_at_lote]);
    }

    public function updateLote(Request $request, $loteId)
    {
        //
        if (!$loteId) {
            return $this->returnFail(400, "ID no enviado.");
        }

        $lote = Lot::where('id_lote', $loteId)->first();

        // $dismantlingsOfProducts= Dismantling::where('piece_product_id', $lot)->delete();


        if (!$lote) {
            return $this->returnFail(404, "Lote no encontrado.");
        }
 
        $lote->due_date = $request->due_date;
        $lote->quantity = floatval($request->quantity);
        $lote->save();

        return $this->returnSuccess(200, [$lote]);
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
