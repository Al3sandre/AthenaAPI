<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }

        if (Auth::user()->role === 'admin') {
            return response()->json(User::all(), 200);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Utilisateur introuvable'], 404);
        }

        if (Auth::check() && (Auth::user()->role === 'admin' || Auth::id() === $user->id)) {
            return response()->json($user, 200);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8|confirmed',
            'role' => 'sometimes|string|in:admin,store', // Validation pour le rôle
            'avatar' => 'sometimes|image|max:2048', // Validation pour l'avatar
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->update($request->only(['name', 'email', 'password','role']));

        return response()->json([
            'message' => 'Utilisateur mis à jour avec succès.',
            'user' => $user,
        ], 200);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (Auth::user()->role === 'admin') {
            try {
                $user->delete();
                return response()->json(['message' => 'Utilisateur supprimé avec succès.'], 204);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Erreur lors de la suppression de l\'utilisateur'], 500);
            }
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
    public function store(Request $request)
    {
        // Valider les données entrantes
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,store',
        ]);

        // Créer un nouvel utilisateur
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'],
        ]);

        return response()->json([
            'message' => 'Utilisateur créé avec succès.',
            'user' => $user,
        ], 201);
    }
}
