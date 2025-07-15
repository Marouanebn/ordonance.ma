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

        if ($user->hasRole('pharmacien')) {
            $medicaments = Medicament::orderBy('nom', 'asc')->paginate(10);
        } elseif ($user->hasRole('admin')) {
            $medicaments = Medicament::orderBy('nom', 'asc')->paginate(10);
        } else {
            return response()->json(['message' => 'Forbidden: Only pharmaciens or admin can view medicaments'], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => $medicaments
        ]);
    }

    public function store(Request $request)
    {
        if (!$request->user()->hasAnyRole(['pharmacien', 'admin'])) {
            return response()->json(['message' => 'Forbidden: Only pharmaciens or admin can create medicaments'], 403);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'description' => 'sometimes|string|max:500',
            'prix' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categorie' => 'sometimes|string|max:100',
            'forme' => 'sometimes|string|max:100',
            'dosage' => 'sometimes|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $medicament = Medicament::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Medicament created successfully',
            'data' => $medicament
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();

        if (!$user->hasAnyRole(['pharmacien', 'admin'])) {
            return response()->json(['message' => 'Forbidden: Only pharmaciens or admin can view medicaments'], 403);
        }

        $medicament = Medicament::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $medicament
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!$request->user()->hasAnyRole(['pharmacien', 'admin'])) {
            return response()->json(['message' => 'Forbidden: Only pharmaciens or admin can update medicaments'], 403);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:500',
            'prix' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'categorie' => 'sometimes|string|max:100',
            'forme' => 'sometimes|string|max:100',
            'dosage' => 'sometimes|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $medicament = Medicament::findOrFail($id);
        $medicament->update($request->all());

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
