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
        $arrivalProduct = ArrivalProduct::create($request->all());
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
