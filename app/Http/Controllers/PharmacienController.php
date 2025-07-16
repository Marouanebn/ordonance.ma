<?php

namespace App\Http\Controllers;

use App\Models\Pharmacien;
use App\Models\DemandeLaboratoire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PharmacienController extends Controller
{
    public function index()
    {
        if (!request()->user()->hasAnyRole(['pharmacien', 'admin'])) {
            return response()->json(['message' => 'Forbidden: Only pharmaciens or admin can view pharmaciens'], 403);
        }
        return response()->json(Pharmacien::all());
    }

    public function store(Request $request)
    {
        if (!$request->user()->hasAnyRole(['pharmacien', 'admin'])) {
            return response()->json(['message' => 'Forbidden: Only pharmaciens or admin can create pharmaciens'], 403);
        }
        $pharmacien = Pharmacien::create($request->all());
        return response()->json($pharmacien, 201);
    }

    public function show($id)
    {
        if (!request()->user()->hasAnyRole(['pharmacien', 'admin'])) {
            return response()->json(['message' => 'Forbidden: Only pharmaciens or admin can view pharmaciens'], 403);
        }
        $pharmacien = Pharmacien::findOrFail($id);
        return response()->json($pharmacien);
    }

    public function update(Request $request, $id)
    {
        if (!$request->user()->hasAnyRole(['pharmacien', 'admin'])) {
            return response()->json(['message' => 'Forbidden: Only pharmaciens or admin can update pharmaciens'], 403);
        }
        $pharmacien = Pharmacien::findOrFail($id);
        $pharmacien->update($request->all());
        return response()->json($pharmacien);
    }

    public function destroy($id)
    {
        if (!request()->user()->hasAnyRole(['pharmacien', 'admin'])) {
            return response()->json(['message' => 'Forbidden: Only pharmaciens or admin can delete pharmaciens'], 403);
        }
        $pharmacien = Pharmacien::findOrFail($id);
        $pharmacien->delete();
        return response()->json(['message' => 'Pharmacien deleted']);
    }

    public function demandes($id)
    {
        $pharmacien = Pharmacien::findOrFail($id);
        $demandes = $pharmacien->demandes()->with('laboratoire')->get();
        return response()->json($demandes);
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        if (!$user->hasRole('pharmacien')) {
            return response()->json(['message' => 'Forbidden: Only pharmaciens can access this'], 403);
        }
        $pharmacien = $user->pharmacien;
        if (!$pharmacien) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pharmacien profile not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $user,
                'pharmacien' => $pharmacien
            ]
        ]);
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom_pharmacie' => 'sometimes|string|max:255',
            'telephone' => 'sometimes|string|max:20',
            'email' => 'sometimes|email|unique:users,email,' . $request->user()->id,
            'adresse_pharmacie' => 'sometimes|string|max:255',
            'ville' => 'sometimes|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        if (!$user->hasRole('pharmacien')) {
            return response()->json(['message' => 'Forbidden: Only pharmaciens can access this'], 403);
        }
        $pharmacien = $user->pharmacien;
        if (!$pharmacien) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pharmacien profile not found'
            ], 404);
        }

        // Update user data
        if ($request->has('email')) {
            $user->update(['email' => $request->email]);
        }

        // Update pharmacien data
        $pharmacien->update($request->only([
            'nom_pharmacie',
            'telephone',
            'adresse_pharmacie',
            'ville'
        ]));

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => [
                'user' => $user->fresh(),
                'pharmacien' => $pharmacien->fresh()
            ]
        ]);
    }

    public function searchPatients(Request $request)
    {
        if (!$request->user()->hasRole('pharmacien')) {
            return response()->json(['message' => 'Forbidden: Only pharmaciens can search patients'], 403);
        }
        $query = $request->input('query');
        $patients = \App\Models\Patient::with('user')
            ->where(function ($q) use ($query) {
                $q->where('id', $query)
                    ->orWhere('nom_complet', 'like', "%$query%")
                    ->orWhereHas('user', function ($uq) use ($query) {
                        $uq->where('name', 'like', "%$query%")
                            ->orWhere('email', 'like', "%$query%");
                    });
            })
            ->paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $patients
        ]);
    }

    public function patientOrdonnances(Request $request, $patientId)
    {
        if (!$request->user()->hasRole('pharmacien')) {
            return response()->json(['message' => 'Forbidden: Only pharmaciens can view patient ordonnances'], 403);
        }
        $patient = \App\Models\Patient::findOrFail($patientId);
        $ordonnances = $patient->ordonnances()
            ->where(function ($q) {
                $q->where('status', 'active')->orWhereNull('status');
            })
            ->with(['medecin.user', 'medicaments'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $ordonnances
        ]);
    }
}
