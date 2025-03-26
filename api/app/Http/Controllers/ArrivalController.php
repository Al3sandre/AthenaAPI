<?php

namespace App\Http\Controllers;

use App\Models\Arrival;
use Illuminate\Http\Request;
use App\Models\ArrivalProduct;

class ArrivalController extends Controller
{
    public function index()
    {
        $arrivals = Arrival::with('products')->get();
        return response()->json($arrivals);
    }

    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0', // Champ obligatoire avec validation numérique
            'status' => 'required|string', // Champ obligatoire avec validation de type string
        ]);

        // Création de l'arrivée
        $arrival = Arrival::create($validated);

        return response()->json($arrival, 201);
    }

    public function show($id)
    {
        // Récupérer l'arrivage avec les produits associés
        $arrival = Arrival::with(['products.product'])->find($id);

        // Vérifier si l'arrivage existe
        if (!$arrival) {
            return response()->json(['message' => 'Arrivage non trouvé.'], 404);
        }

        // Structurer les données pour inclure les détails des produits
        $data = [
            'id' => $arrival->id,
            'amount' => $arrival->amount,
            'status' => $arrival->status,
            'created_at' => $arrival->created_at,
            'updated_at' => $arrival->updated_at,
            'products' => $arrival->products->map(function ($arrivalProduct) {
                return [
                    'id' => $arrivalProduct->id,
                    'product_id' => $arrivalProduct->product_id,
                    'quantity' => $arrivalProduct->quantity,
                    'unit_price' => $arrivalProduct->unit_price,
                    'product' => [
                        'id' => $arrivalProduct->product->id,
                        'name' => $arrivalProduct->product->name,
                        'description' => $arrivalProduct->product->description,
                        'price' => $arrivalProduct->product->price,
                        'image' => $arrivalProduct->product->image,
                    ],
                ];
            }),
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        // Validation des données
        $validated = $request->validate([
            'amount' => 'sometimes|numeric|min:0', // Validation si le champ est présent
            'status' => 'sometimes|string', // Validation si le champ est présent
        ]);

        // Mise à jour de l'arrivée
        $arrival = Arrival::findOrFail($id);
        $arrival->update($validated);

        return response()->json($arrival, 200);
    }

    public function destroy($id)
    {
        Arrival::destroy($id);
        return response()->json(null, 204);
    }
}
