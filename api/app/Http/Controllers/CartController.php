<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return Cart::with('items.product')->get();
    }

    public function store(Request $request)
    {
        $cart = Cart::create($request->all());
        return response()->json($cart, 201);
    }

    public function show($id)
    {
        return Cart::with('items.product')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::findOrFail($id);
        $cart->update($request->all());
        return response()->json($cart, 200);
    }

    public function destroy($id)
    {
        Cart::destroy($id);
        return response()->json(null, 204);
    }
}
