<?php

namespace App\Http\Controllers;

use App\Models\Arrival;
use Illuminate\Http\Request;

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
        $arrival = Arrival::with('products')->findOrFail($id);
        return response()->json($arrival);
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
