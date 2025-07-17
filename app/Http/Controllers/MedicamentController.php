<?php

namespace App\Http\Controllers;

use App\Models\Medicament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedicamentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Allow medecin, pharmacien, and admin
        if ($user->hasAnyRole(['pharmacien', 'admin', 'medecin'])) {
            $medicaments = Medicament::orderBy('nom', 'asc')->paginate(10);
        } else {
            return response()->json(['message' => 'Forbidden: Only pharmaciens, medecins, or admin can view medicaments'], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => $medicaments
        ]);
    }

    public function store(Request $request)
    {
        if (!$request->user()->hasAnyRole(['pharmacien', 'admin', 'medecin'])) {
            return response()->json(['message' => 'Forbidden: Only pharmaciens, medecins, or admin can create medicaments'], 403);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'quantite' => 'required|integer',
            'disponible' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json([
                'status' => 'error',
                'message' => $errors[0] ?? 'Validation error.',
                'errors' => $validator->errors()
            ], 422);
        }

        $medicament = Medicament::create($validator->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Medicament created successfully',
            'data' => $medicament
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();

        if (!$user->hasAnyRole(['pharmacien', 'admin', 'medecin'])) {
            return response()->json(['message' => 'Forbidden: Only pharmaciens, medecins, or admin can view medicaments'], 403);
        }

        $medicament = Medicament::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $medicament
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!$request->user()->hasAnyRole(['pharmacien', 'admin', 'medecin'])) {
            return response()->json(['message' => 'Forbidden: Only pharmaciens, medecins, or admin can update medicaments'], 403);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|string|max:255',
            'quantite' => 'sometimes|integer',
            'disponible' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json([
                'status' => 'error',
                'message' => $errors[0] ?? 'Validation error.',
                'errors' => $validator->errors()
            ], 422);
        }

        $medicament = Medicament::findOrFail($id);
        $medicament->update($validator->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Medicament updated successfully',
            'data' => $medicament
        ]);
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->user()->hasAnyRole(['pharmacien', 'admin'])) {
            return response()->json(['message' => 'Forbidden: Only pharmaciens or admin can delete medicaments'], 403);
        }

        $medicament = Medicament::findOrFail($id);
        $medicament->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Medicament deleted successfully'
        ]);
    }
}
