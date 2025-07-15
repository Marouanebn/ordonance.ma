<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->user();
        if (!$user->hasRole('patient')) {
            return response()->json(['message' => 'Forbidden: Only patients can access this'], 403);
        }
        $patient = $user->patient;
        if (!$patient) {
            return response()->json([
                'status' => 'error',
                'message' => 'Patient profile not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $user,
                'patient' => $patient
            ]
        ]);
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom_complet' => 'sometimes|string|max:255',
            'telephone' => 'sometimes|string|max:20',
            'email' => 'sometimes|email|unique:users,email,' . $request->user()->id,
            'date_naissance' => 'sometimes|date',
            'adresse' => 'sometimes|string|max:255',
            'ville' => 'sometimes|string|max:100',
            'genre' => 'sometimes|in:homme,femme',
            'numero_securite_sociale' => 'sometimes|string|unique:patients,numero_securite_sociale',
            'antecedents_medicaux' => 'sometimes|string',
            'allergies' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        if (!$user->hasRole('patient')) {
            return response()->json(['message' => 'Forbidden: Only patients can access this'], 403);
        }
        $patient = $user->patient;
        if (!$patient) {
            return response()->json([
                'status' => 'error',
                'message' => 'Patient profile not found'
            ], 404);
        }

        // Update user data
        if ($request->has('email')) {
            $user->update(['email' => $request->email]);
        }

        // Update patient data
        $patient->update($request->only([
            'nom_complet',
            'telephone',
            'date_naissance',
            'adresse',
            'ville',
            'genre',
            'numero_securite_sociale',
            'antecedents_medicaux',
            'allergies'
        ]));

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => [
                'user' => $user->fresh(),
                'patient' => $patient->fresh()
            ]
        ]);
    }

    public function ordonnances(Request $request)
    {
        $user = $request->user();
        if (!$user->hasRole('patient')) {
            return response()->json(['message' => 'Forbidden: Only patients can access this'], 403);
        }
        $patient = $user->patient;
        if (!$patient) {
            return response()->json([
                'status' => 'error',
                'message' => 'Patient profile not found'
            ], 404);
        }

        $ordonnances = $patient->ordonnances()
            ->with(['medecin.user', 'medicaments'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $ordonnances
        ]);
    }
}
