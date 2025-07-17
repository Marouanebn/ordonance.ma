<?php

namespace App\Http\Controllers;

use App\Models\Medecin;
use App\Models\Ordonnance;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedecinController extends Controller
{
    public function index()
    {
        if (!request()->user()->hasAnyRole(['medecin', 'admin'])) {
            return response()->json(['message' => 'Forbidden: Only medecins or admin can view medecins'], 403);
        }
        // List all medecins
        return response()->json(Medecin::all());
    }

    public function store(Request $request)
    {
        if (!$request->user()->hasAnyRole(['medecin', 'admin'])) {
            return response()->json(['message' => 'Forbidden: Only medecins or admin can create medecins'], 403);
        }
        $medecin = Medecin::create($request->all());
        return response()->json($medecin, 201);
    }

    public function show($id)
    {
        if (!request()->user()->hasAnyRole(['medecin', 'admin'])) {
            return response()->json(['message' => 'Forbidden: Only medecins or admin can view medecins'], 403);
        }
        $medecin = Medecin::findOrFail($id);
        return response()->json($medecin);
    }

    public function update(Request $request, $id)
    {
        if (!$request->user()->hasAnyRole(['medecin', 'admin'])) {
            return response()->json(['message' => 'Forbidden: Only medecins or admin can update medecins'], 403);
        }
        $medecin = Medecin::findOrFail($id);
        $medecin->update($request->all());
        return response()->json($medecin);
    }

    public function destroy($id)
    {
        if (!request()->user()->hasAnyRole(['medecin', 'admin'])) {
            return response()->json(['message' => 'Forbidden: Only medecins or admin can delete medecins'], 403);
        }
        $medecin = Medecin::findOrFail($id);
        $medecin->delete();
        return response()->json(['message' => 'Medecin deleted']);
    }

    public function ordonnances($id)
    {
        $medecin = Medecin::findOrFail($id);
        $ordonnances = $medecin->ordonnances()->with('medicaments')->get();
        return response()->json($ordonnances);
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        if (!$user->hasRole('medecin')) {
            return response()->json(['message' => 'Forbidden: Only medecins can access this'], 403);
        }
        $medecin = $user->medecin;
        if (!$medecin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Medecin profile not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $user,
                'medecin' => $medecin
            ]
        ]);
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom_complet' => 'sometimes|string|max:255',
            'telephone' => 'sometimes|string|max:20',
            'email' => 'sometimes|email|unique:users,email,' . $request->user()->id,
            'specialite' => 'sometimes|string|max:255',
            'numero_cnom' => 'sometimes|string|unique:medecins,numero_cnom',
            'adresse_cabinet' => 'sometimes|string|max:255',
            'ville' => 'sometimes|string|max:100',
            // File fields
            'identity_document' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'diploma' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'attestation_cnom' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:2048',
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
        if (!$user->hasRole('medecin')) {
            return response()->json(['message' => 'Forbidden: Only medecins can access this'], 403);
        }
        $medecin = $user->medecin;
        if (!$medecin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Medecin profile not found'
            ], 404);
        }

        // Update user data
        if ($request->has('email')) {
            $user->update(['email' => $request->email]);
        }

        // Handle file uploads
        $updateData = $request->only([
            'nom_complet',
            'telephone',
            'specialite',
            'numero_cnom',
            'adresse_cabinet',
            'ville'
        ]);
        if ($request->hasFile('piece_identite_recto')) {
            $updateData['piece_identite_recto'] = $request->file('piece_identite_recto')->store('documents/identity', 'public');
        }
        if ($request->hasFile('piece_identite_verso')) {
            $updateData['piece_identite_verso'] = $request->file('piece_identite_verso')->store('documents/identity', 'public');
        }
        if ($request->hasFile('diplome')) {
            $updateData['diplome'] = $request->file('diplome')->store('documents/diplomas', 'public');
        }
        if ($request->hasFile('attestation_cnom')) {
            $updateData['attestation_cnom'] = $request->file('attestation_cnom')->store('documents/attestations', 'public');
        }
        $medecin->update($updateData);

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => [
                'user' => $user->fresh(),
                'medecin' => $medecin->fresh()
            ]
        ]);
    }

    public function patients(Request $request)
    {
        $user = $request->user();
        if (!$user->hasRole('medecin')) {
            return response()->json(['message' => 'Forbidden: Only medecins can access this'], 403);
        }
        $medecin = $user->medecin;
        if (!$medecin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Medecin profile not found'
            ], 404);
        }

        $patients = $medecin->patients()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $patients
        ]);
    }
}
