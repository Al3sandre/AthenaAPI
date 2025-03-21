<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        if (Auth::user()->role === 'admin' || Auth::id() === $user->id) {
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
                'password' => 'sometimes|string|min:8|confirmed',
            ]);

            $user->update($request->only(['name', 'email', 'password']));

            return response()->json([
                'message' => 'Utilisateur mis à jour avec succès.',
                'user' => $user,
            ], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
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
}
