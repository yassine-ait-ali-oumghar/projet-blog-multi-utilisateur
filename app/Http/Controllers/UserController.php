<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('posts')->orderBy('created_at', 'desc')->get();
        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::with('posts')->findOrFail($id);
        return response()->json($user);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
        ];

        $validatedData = $request->validate($rules);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:6'],
        ];

        $validatedData = $request->validate($rules);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Supprimer d'abord tous ses posts associés pour éviter les contraintes FK
        // (Les commentaires sur ces posts seront supprimés via cascade DB si configuré, 
        // ou on peut aussi faire $user->posts()->each(fn($p) => $p->comments()->delete()); 
        // mais le user a demandé le snippet simple)
        $user->posts()->delete();
        
        $user->delete();

        return response()->json(null, 204);
    }
}
