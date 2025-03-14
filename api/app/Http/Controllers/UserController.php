<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            return User::all();
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        if (Auth::user()->role === 'admin' || Auth::id() === $user->id) {
            return $user;
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if (Auth::user()->role === 'admin' || Auth::id() === $user->id) {
            $user->update($request->all());
            return response()->json($user, 200);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (Auth::user()->role === 'admin') {
            $user->delete();
            return response()->json(null, 204);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
