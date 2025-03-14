<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    public function index()
    {
        return CartItem::all();
    }

    public function store(Request $request)
    {
        $cartItem = CartItem::create($request->all());
        return response()->json($cartItem, 201);
    }

    public function show($id)
    {
        return CartItem::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->update($request->all());
        return response()->json($cartItem, 200);
    }

    public function destroy($id)
    {
        CartItem::destroy($id);
        return response()->json(null, 204);
    }
}
