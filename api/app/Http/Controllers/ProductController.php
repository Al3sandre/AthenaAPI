<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('categories')->get(); // Charger les catégories liées
        return response()->json($products, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'categories' => 'required|array', // Valide que les catégories sont un tableau
            'categories.*' => 'exists:categories,id', // Valide que chaque ID de catégorie existe
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ], [
            'name.required' => 'Le nom du produit est obligatoire.',
            'price.required' => 'Le prix du produit est obligatoire.',
            'categories.*.exists' => 'Une ou plusieurs catégories sont invalides.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            try {
                $imagePath = $request->file('image')->store('images', 'public');
            } catch (\Exception $e) {
                return response()->json(['error' => 'Erreur lors du téléchargement de l\'image'], 500);
            }
        }

        // Créer le produit
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath,
        ]);

        // Associer les catégories au produit
        $product->categories()->attach($request->categories);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product,
            'image_url' => $imagePath ? asset('storage/' . $imagePath) : null,
        ], 201);
    }
    public function show($id)
    {
        $product = Product::with('categories')->findOrFail($id); // Charger les catégories liées
        return response()->json($product, 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'categories' => 'nullable|array', // Rendre le champ facultatif
            'categories.*' => 'exists:categories,id', // Valide que chaque ID de catégorie existe
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp',
        ], [
            'name.required' => 'Le nom du produit est obligatoire.',
            'price.required' => 'Le prix du produit est obligatoire.',
            'categories.*.exists' => 'Une ou plusieurs catégories sont invalides.',
        ]);

        $product = Product::findOrFail($id);

        // Gérer l'image
        $imagePath = $product->image; // Conserver l'image existante par défaut
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            try {
                $imagePath = $request->file('image')->store('images', 'public');
            } catch (\Exception $e) {
                return response()->json(['error' => 'Erreur lors du téléchargement de l\'image'], 500);
            }
        }

        // Mettre à jour les champs du produit
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath,
        ]);

        // Mettre à jour les catégories associées
        if ($request->has('categories')) {
            $product->categories()->sync($request->categories); // Synchroniser les catégories
        }

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product->load('categories'), // Charger les catégories associées
            'image_url' => $imagePath ? asset('storage/' . $imagePath) : null,
        ], 200);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Supprimer l'image si elle existe
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
    public function adjustStock(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer',
        ]);

        $product = Product::findOrFail($id);

        $newStock = $product->stock + $request->quantity;

        if ($newStock < 0) {
            return response()->json(['error' => 'Stock insuffisant'], 400);
        }

        $product->stock = $newStock;
        $product->save();

        return response()->json(['message' => 'Stock ajusté avec succès', 'product' => $product], 200);
    }
}
