<?php

namespace App\Http\Controllers;

use App\Models\Arrival;
use Illuminate\Http\Request;

class ArrivalController extends Controller
{
    public function index()
    {
        return Arrival::with('products')->get();
    }

    public function store(Request $request)
    {
        $arrival = Arrival::create($request->all());
        return response()->json($arrival, 201);
    }

    public function show($id)
    {
        return Arrival::with('products')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $arrival = Arrival::findOrFail($id);
        $arrival->update($request->all());
        return response()->json($arrival, 200);
    }

    public function destroy($id)
    {
        Arrival::destroy($id);
        return response()->json(null, 204);
    }
}
