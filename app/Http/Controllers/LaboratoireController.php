<?php

namespace App\Http\Controllers;

use App\Models\Laboratoire;
use App\Models\DemandeLaboratoire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LaboratoireController extends Controller
{
    public function index()
    {
        if (!request()->user()->hasAnyRole(['laboratoire', 'admin'])) {
            return response()->json(['message' => 'Forbidden: Only laboratoires or admin can view laboratoires'], 403);
        }
        return response()->json(Laboratoire::all());
    }

    public function store(Request $request)
    {
        if (!$request->user()->hasAnyRole(['laboratoire', 'admin'])) {
            return response()->json(['message' => 'Forbidden: Only laboratoires or admin can create laboratoires'], 403);
        }
        $laboratoire = Laboratoire::create($request->all());
        return response()->json($laboratoire, 201);
    }

    public function show($id)
    {
        if (!request()->user()->hasAnyRole(['laboratoire', 'admin'])) {
            return response()->json(['message' => 'Forbidden: Only laboratoires or admin can view laboratoires'], 403);
        }
        $laboratoire = Laboratoire::findOrFail($id);
        return response()->json($laboratoire);
    }

    public function update(Request $request, $id)
    {
        if (!$request->user()->hasAnyRole(['laboratoire', 'admin'])) {
            return response()->json(['message' => 'Forbidden: Only laboratoires or admin can update laboratoires'], 403);
        }
        $laboratoire = Laboratoire::findOrFail($id);
        $laboratoire->update($request->all());
        return response()->json($laboratoire);
    }

    public function destroy($id)
    {
        if (!request()->user()->hasAnyRole(['laboratoire', 'admin'])) {
            return response()->json(['message' => 'Forbidden: Only laboratoires or admin can delete laboratoires'], 403);
        }
        $laboratoire = Laboratoire::findOrFail($id);
        $laboratoire->delete();
        return response()->json(['message' => 'Laboratoire deleted']);
    }

    public function demandes($id)
    {
        $laboratoire = Laboratoire::findOrFail($id);
        $demandes = $laboratoire->demandes()->with('pharmacien')->get();
        return response()->json($demandes);
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        if (!$user->hasRole('laboratoire')) {
            return response()->json(['message' => 'Forbidden: Only laboratoires can access this'], 403);
        }
        $laboratoire = $user->laboratoire;
        if (!$laboratoire) {
            return response()->json([
                'status' => 'error',
                'message' => 'Laboratoire profile not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $user,
                'laboratoire' => $laboratoire
            ]
        ]);
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom_complet' => 'sometimes|string|max:255',
            'telephone' => 'sometimes|string|max:20',
            'email' => 'sometimes|email|unique:users,email,' . $request->user()->id,
            'numero_laboratoire' => 'sometimes|string|unique:laboratoires,numero_laboratoire',
            'adresse_laboratoire' => 'sometimes|string|max:255',
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
        if (!$user->hasRole('laboratoire')) {
            return response()->json(['message' => 'Forbidden: Only laboratoires can access this'], 403);
        }
        $laboratoire = $user->laboratoire;
        if (!$laboratoire) {
            return response()->json([
                'status' => 'error',
                'message' => 'Laboratoire profile not found'
            ], 404);
        }

        // Update user data
        if ($request->has('email')) {
            $user->update(['email' => $request->email]);
        }

        // Update laboratoire data
        $laboratoire->update($request->only([
            'nom_complet',
            'telephone',
            'numero_laboratoire',
            'adresse_laboratoire',
            'ville'
        ]));

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => [
                'user' => $user->fresh(),
                'laboratoire' => $laboratoire->fresh()
            ]
        ]);
    }
}
