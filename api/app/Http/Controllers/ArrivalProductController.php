<?php

namespace App\Http\Controllers;

use App\Models\ArrivalProduct;
use Illuminate\Http\Request;

class ArrivalProductController extends Controller
{
    public function index()
    {
        return ArrivalProduct::all();
    }

    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'arrival_id' => 'required|exists:arrivals,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0', // Validation pour unit_price
        ]);

        // Création du produit dans l'arrivage
        $arrivalProduct = ArrivalProduct::create($validated);

        return response()->json($arrivalProduct, 201);
    }

    public function show($id)
    {
        return ArrivalProduct::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $arrivalProduct = ArrivalProduct::findOrFail($id);
        $arrivalProduct->update($request->all());
        return response()->json($arrivalProduct, 200);
    }

    public function destroy($id)
    {
        ArrivalProduct::destroy($id);
        return response()->json(null, 204);
    }
}
