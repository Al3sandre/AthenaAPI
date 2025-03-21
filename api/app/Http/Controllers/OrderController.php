<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        try {
            $orders = Order::with('items.product')->paginate(30); // Utilisez la pagination

            return response()->json([
                'data' => $orders->items(), // Les commandes
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des commandes.'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validez les données entrantes
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'total_amount' => 'required|numeric|min:0',
                'status' => 'required|string',
                'items' => 'required|array',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.price' => 'required|numeric|min:0',
            ]);

            // Créez la commande
            $order = Order::create([
                'user_id' => $validated['user_id'],
                'total_amount' => $validated['total_amount'],
                'status' => $validated['status'],
            ]);

            // Ajoutez les items à la commande
            foreach ($validated['items'] as $item) {
                $order->items()->create($item);
            }

            // Retournez la commande avec les items associés
            return response()->json($order->load('items'), 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la création de la commande.'], 500);
        }
    }

    public function show($id)
    {
        return Order::with('items.product')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update($request->all());
        return response()->json($order, 200);
    }

    public function destroy($id)
    {
        Order::destroy($id);
        return response()->json(null, 204);
    }
}
