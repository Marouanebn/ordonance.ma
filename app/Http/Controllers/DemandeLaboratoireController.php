<?php

namespace App\Http\Controllers;

use App\Models\DemandeLaboratoire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DemandeLaboratoireController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('pharmacien')) {
            $pharmacien = $user->pharmacien;
            if (!$pharmacien) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pharmacien profile not found'
                ], 404);
            }
            $demandes = $pharmacien->demandes()
                ->with(['laboratoire.user', 'medicaments'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } elseif ($user->hasRole('admin')) {
            $demandes = DemandeLaboratoire::with(['pharmacien.user', 'laboratoire.user', 'medicaments'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            return response()->json(['message' => 'Forbidden: Only pharmaciens or admin can view demandes'], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => $demandes
        ]);
    }

    public function store(Request $request)
    {
        if (!$request->user()->hasAnyRole(['pharmacien', 'admin'])) {
            return response()->json(['message' => 'Forbidden: Only pharmaciens or admin can create demandes'], 403);
        }

        $validator = Validator::make($request->all(), [
            'laboratoire_id' => 'required|exists:laboratoires,id',
            'medicaments' => 'required|array',
            'medicaments.*.medicament_id' => 'required|exists:medicaments,id',
            'medicaments.*.quantite' => 'required|integer|min:1',
            'date_demande' => 'required|date',
            'commentaire' => 'sometimes|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $pharmacien = $user->pharmacien;

        if ($user->hasRole('pharmacien') && !$pharmacien) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pharmacien profile not found'
            ], 404);
        }

        $demande = DemandeLaboratoire::create([
            'pharmacien_id' => $pharmacien ? $pharmacien->id : $request->pharmacien_id,
            'laboratoire_id' => $request->laboratoire_id,
            'date_demande' => $request->date_demande,
            'statut' => 'en_attente',
            'commentaire' => $request->commentaire,
        ]);

        // Attach medicaments with pivot data
        foreach ($request->medicaments as $medicament) {
            $demande->medicaments()->attach($medicament['medicament_id'], [
                'quantite' => $medicament['quantite']
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Demande created successfully',
            'data' => $demande->load(['pharmacien.user', 'laboratoire.user', 'medicaments'])
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $demande = DemandeLaboratoire::with(['pharmacien.user', 'laboratoire.user', 'medicaments'])->findOrFail($id);

        $user = $request->user();

        // Check if user has permission to view this demande
        if ($user->hasRole('pharmacien')) {
            $pharmacien = $user->pharmacien;
            if (!$pharmacien || $demande->pharmacien_id !== $pharmacien->id) {
                return response()->json(['message' => 'Forbidden: You can only view your own demandes'], 403);
            }
        } elseif ($user->hasRole('laboratoire')) {
            $laboratoire = $user->laboratoire;
            if (!$laboratoire || $demande->laboratoire_id !== $laboratoire->id) {
                return response()->json(['message' => 'Forbidden: You can only view your own demandes'], 403);
            }
        } elseif (!$user->hasRole('admin')) {
            return response()->json(['message' => 'Forbidden: Access denied'], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => $demande
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!$request->user()->hasAnyRole(['laboratoire', 'admin'])) {
            return response()->json(['message' => 'Forbidden: Only laboratoires or admin can update demandes'], 403);
        }

        $validator = Validator::make($request->all(), [
            'statut' => 'sometimes|in:en_attente,en_cours,terminee,annulee',
            'commentaire' => 'sometimes|string|max:500',
            'date_realisation' => 'sometimes|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $demande = DemandeLaboratoire::findOrFail($id);
        $user = $request->user();

        if ($user->hasRole('laboratoire')) {
            $laboratoire = $user->laboratoire;
            if (!$laboratoire || $demande->laboratoire_id != $laboratoire->id) {
                return response()->json(['message' => 'Forbidden: You can only update your own demandes'], 403);
            }
        }

        $demande->update($request->only([
            'statut',
            'commentaire',
            'date_realisation'
        ]));

        return response()->json([
            'status' => 'success',
            'message' => 'Demande updated successfully',
            'data' => $demande->fresh()->load(['pharmacien.user', 'laboratoire.user', 'medicaments'])
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $demande = DemandeLaboratoire::findOrFail($id);
        $user = $request->user();

        if (!$user->hasAnyRole(['laboratoire', 'admin'])) {
            return response()->json(['message' => 'Forbidden: Only laboratoires or admin can delete demandes'], 403);
        }

        if ($user->hasRole('laboratoire')) {
            $laboratoire = $user->laboratoire;
            if (!$laboratoire || $demande->laboratoire_id != $laboratoire->id) {
                return response()->json(['message' => 'Forbidden: You can only delete your own demandes'], 403);
            }
        }

        $demande->medicaments()->detach();
        $demande->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Demande deleted successfully'
        ]);
    }

    public function laboratoireDemandes(Request $request)
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

        $demandes = $laboratoire->demandes()
            ->with(['pharmacien.user', 'medicaments'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $demandes
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'statut' => 'required|in:en_attente,en_cours,terminee,annulee',
            'commentaire' => 'sometimes|string|max:500',
            'date_realisation' => 'sometimes|date',
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
            return response()->json(['message' => 'Forbidden: Only laboratoires can update demandes'], 403);
        }
        $laboratoire = $user->laboratoire;
        if (!$laboratoire) {
            return response()->json([
                'status' => 'error',
                'message' => 'Laboratoire profile not found'
            ], 404);
        }

        $demande = DemandeLaboratoire::findOrFail($id);
        if ($demande->laboratoire_id != $laboratoire->id) {
            return response()->json(['message' => 'Forbidden: You can only update your own demandes'], 403);
        }

        $demande->update($request->only([
            'statut',
            'commentaire',
            'date_realisation'
        ]));

        return response()->json([
            'status' => 'success',
            'message' => 'Demande status updated successfully',
            'data' => $demande->fresh()->load(['pharmacien.user', 'medicaments'])
        ]);
    }
}
