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
            'cin' => 'sometimes|string|max:20|unique:patients,cin,' . ($request->user()->patient->id ?? 'NULL'),
            'telephone' => 'sometimes|string|max:20',
            'email' => 'sometimes|email|unique:users,email,' . $request->user()->id,
            'date_naissance' => 'sometimes|date',
            'genre' => 'sometimes|in:homme,femme',
            'numero_securite_sociale' => 'sometimes|string|unique:patients,numero_securite_sociale',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json([
                'status' => 'error',
                'message' => $errors[0] ?? 'Validation error.',
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
            'cin',
            'telephone',
            'date_naissance',
            'genre',
            'numero_securite_sociale',
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

    public function index(Request $request)
    {
        // Only medecin, admin, or pharmacien can fetch all patients
        if (!$request->user()->hasAnyRole(['medecin', 'admin', 'pharmacien'])) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $patients = \App\Models\Patient::with('user')->orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $patients
        ]);
    }
}
