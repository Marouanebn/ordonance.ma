<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:patient,medecin,pharmacien,laboratoire,admin',

            // Patient fields
            'telephone' => 'required_if:role,patient|string|max:20',
            'date_naissance' => 'required_if:role,patient|date',
            'adresse' => 'required_if:role,patient|string|max:255',
            'ville' => 'required_if:role,patient|string|max:100',
            'genre' => 'required_if:role,patient|in:homme,femme',
            'numero_securite_sociale' => 'nullable|string|unique:patients,numero_securite_sociale',
            'antecedents_medicaux' => 'nullable|string',
            'allergies' => 'nullable|string',

            // Medecin fields
            'telephone_medecin' => 'required_if:role,medecin|string|max:20',
            'numero_cnom' => 'required_if:role,medecin|string|unique:medecins,numero_cnom',
            'specialite' => 'required_if:role,medecin|string|max:255',
            'adresse_cabinet' => 'required_if:role,medecin|string|max:255',
            'ville_medecin' => 'required_if:role,medecin|string|max:100',
            'statut_medecin' => 'required_if:role,medecin|in:actif,inactif',

            // Pharmacien fields
            'nom_pharmacie' => 'required_if:role,pharmacien|string|max:255',
            'telephone_pharmacien' => 'required_if:role,pharmacien|string|max:20',
            'adresse_pharmacie' => 'required_if:role,pharmacien|string|max:255',
            'ville_pharmacien' => 'required_if:role,pharmacien|string|max:100',
            'statut_pharmacien' => 'required_if:role,pharmacien|in:actif,inactif',

            // Laboratoire fields
            'nom_responsable' => 'required_if:role,laboratoire|string|max:255',
            'nom_laboratoire' => 'required_if:role,laboratoire|string|max:255',
            'telephone_laboratoire' => 'required_if:role,laboratoire|string|max:20',
            'adresse_laboratoire' => 'required_if:role,laboratoire|string|max:255',
            'ville_laboratoire' => 'required_if:role,laboratoire|string|max:100',
            'numero_autorisation' => 'required_if:role,laboratoire|string|unique:laboratoires,numero_autorisation',
            'statut_laboratoire' => 'required_if:role,laboratoire|in:actif,inactif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        // Automatically create the corresponding profile model
        if ($request->role === 'patient') {
            \App\Models\Patient::create([
                'user_id' => $user->id,
                'nom_complet' => $request->name,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'date_naissance' => $request->date_naissance,
                'adresse' => $request->adresse,
                'ville' => $request->ville,
                'genre' => $request->genre,
                'numero_securite_sociale' => $request->numero_securite_sociale,
                'antecedents_medicaux' => $request->antecedents_medicaux,
                'allergies' => $request->allergies,
            ]);
        } elseif ($request->role === 'medecin') {
            \App\Models\Medecin::create([
                'user_id' => $user->id,
                'nom_complet' => $request->name,
                'telephone' => $request->telephone_medecin,
                'numero_cnom' => $request->numero_cnom,
                'specialite' => $request->specialite,
                'adresse_cabinet' => $request->adresse_cabinet,
                'ville' => $request->ville_medecin,
                'statut' => $request->statut_medecin,
            ]);
        } elseif ($request->role === 'pharmacien') {
            \App\Models\Pharmacien::create([
                'user_id' => $user->id,
                'nom_pharmacie' => $request->nom_pharmacie,
                'telephone' => $request->telephone_pharmacien,
                'adresse_pharmacie' => $request->adresse_pharmacie,
                'ville' => $request->ville_pharmacien,
                'statut' => $request->statut_pharmacien,
            ]);
        } elseif ($request->role === 'laboratoire') {
            \App\Models\Laboratoire::create([
                'user_id' => $user->id,
                'nom_responsable' => $request->nom_responsable,
                'nom_laboratoire' => $request->nom_laboratoire,
                'telephone' => $request->telephone_laboratoire,
                'adresse' => $request->adresse_laboratoire,
                'ville' => $request->ville_laboratoire,
                'numero_autorisation' => $request->numero_autorisation,
                'statut' => $request->statut_laboratoire,
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user->load('roles'),
                'token' => $token
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'user' => $user->load('roles'),
                'token' => $token
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ]);
    }

    public function user(Request $request)
    {
        $user = $request->user()->load('roles');

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $user
            ]
        ]);
    }

    public function allUsers(Request $request)
    {
        $users = User::with('roles')->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    public function statistics(Request $request)
    {
        $stats = [
            'total_users' => User::count(),
            'total_medecins' => User::role('medecin')->count(),
            'total_pharmaciens' => User::role('pharmacien')->count(),
            'total_laboratoires' => User::role('laboratoire')->count(),
            'total_patients' => User::role('patient')->count(),
        ];

        return response()->json([
            'status' => 'success',
            'data' => $stats
        ]);
    }
}
