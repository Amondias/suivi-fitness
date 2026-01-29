<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;    
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * REGISTER
     * POST /api/auth/register
     */
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'phone' => $validatedData['phone'] ?? null,
            'address' => $validatedData['address'] ?? null,
            'date_of_birth' => $validatedData['date_of_birth'] ?? null,
            'gender' => $validatedData['gender'] ?? null,
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Inscription réussie',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    /**
     * LOGIN
     * POST /api/auth/login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Identifiants incorrects'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * PROFILE
     * GET /api/auth/profile
     */
    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * UPDATE PROFILE
     * PUT /api/auth/profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $user->update($request->only([
            'name',
            'phone',
            'address',
            'date_of_birth',
            'gender',
        ]));

        return response()->json([
            'message' => 'Profil mis à jour',
            'user' => $user
        ]);
    }

    /**
     * LOGOUT
     * POST /api/auth/logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie'
        ]);
    }

    /**
     * ASSIGN ROLE
     * POST /api/users/{id}/assign-role
     */
    public function assignRole(Request $request, $id)
    {
        $validatedData = $request->validate([
            'role' => 'required|in:client,coach,admin',
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }

        $user->update([
            'role' => $validatedData['role']
        ]);

        return response()->json([
            'message' => 'Rôle attribué avec succès',
            'user' => $user
        ]);
    }
}
