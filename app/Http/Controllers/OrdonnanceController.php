<?php

namespace App\Http\Controllers;

use App\Models\Ordonnance;
use App\Models\Medicament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class OrdonnanceController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('medecin')) {
            $medecin = $user->medecin;
            if (!$medecin) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Medecin profile not found'
                ], 404);
            }
            $ordonnances = $medecin->ordonnances()
                ->with(['patient.user', 'medicaments'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } elseif ($user->hasRole('admin')) {
            $ordonnances = Ordonnance::with(['patient.user', 'medecin.user', 'medicaments'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            return response()->json(['message' => 'Forbidden: Only medecins or admin can view ordonnances'], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => $ordonnances
        ]);
    }

    public function store(Request $request)
    {
        if (!$request->user()->hasAnyRole(['medecin', 'admin'])) {
            return response()->json(['message' => 'Forbidden: Only medecins or admin can create ordonnances'], 403);
        }

        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'medicaments' => 'required|array',
            'medicaments.*.medicament_id' => 'required|exists:medicaments,id',
            'medicaments.*.quantite' => 'required|integer|min:1',
            'date_prescription' => 'required|date',
            'detail' => 'sometimes|string|max:500',
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
        $medecin = $user->medecin;

        if ($user->hasRole('medecin') && !$medecin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Medecin profile not found'
            ], 404);
        }

        $ordonnance = Ordonnance::create([
            'patient_id' => $request->patient_id,
            'medecin_id' => $medecin ? $medecin->id : $request->medecin_id,
            'date_prescription' => $request->date_prescription,
            'detail' => $request->detail,
        ]);

        // Attach medicaments with pivot data
        foreach ($request->medicaments as $medicament) {
            $ordonnance->medicaments()->attach($medicament['medicament_id'], [
                'quantite' => $medicament['quantite']
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Ordonnance created successfully',
            'data' => $ordonnance->load(['patient.user', 'medecin.user', 'medicaments'])
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $ordonnance = Ordonnance::with(['patient.user', 'medecin.user', 'medicaments'])->findOrFail($id);

        $user = $request->user();

        // Check if user has permission to view this ordonnance
        if ($user->hasRole('medecin')) {
            $medecin = $user->medecin;
            if (!$medecin || $ordonnance->medecin_id !== $medecin->id) {
                return response()->json(['message' => 'Forbidden: You can only view your own ordonnances'], 403);
            }
        } elseif ($user->hasRole('patient')) {
            $patient = $user->patient;
            if (!$patient || $ordonnance->patient_id !== $patient->id) {
                return response()->json(['message' => 'Forbidden: You can only view your own ordonnances'], 403);
            }
        } elseif (!$user->hasRole('admin')) {
            return response()->json(['message' => 'Forbidden: Access denied'], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => $ordonnance
        ]);
    }

    public function update(Request $request, $id)
    {
        $ordonnance = Ordonnance::findOrFail($id);
        $user = $request->user();

        if (
            !$user->hasAnyRole(['medecin', 'admin']) ||
            ($user->hasRole('medecin') && $ordonnance->medecin_id !== $user->medecin->id)
        ) {
            return response()->json(['message' => 'Forbidden: You can only update your own ordonnances'], 403);
        }

        $validator = Validator::make($request->all(), [
            'medicaments' => 'sometimes|array',
            'medicaments.*.medicament_id' => 'required_with:medicaments|exists:medicaments,id',
            'medicaments.*.quantite' => 'required_with:medicaments|integer|min:1',
            'date_prescription' => 'sometimes|date',
            'detail' => 'sometimes|string|max:500',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json([
                'status' => 'error',
                'message' => $errors[0] ?? 'Validation error.',
                'errors' => $validator->errors()
            ], 422);
        }

        $ordonnance->update($request->only([
            'date_prescription',
            'detail'
        ]));

        // Update medicaments if provided
        if ($request->has('medicaments')) {
            $ordonnance->medicaments()->detach();
            foreach ($request->medicaments as $medicament) {
                $ordonnance->medicaments()->attach($medicament['medicament_id'], [
                    'quantite' => $medicament['quantite']
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Ordonnance updated successfully',
            'data' => $ordonnance->fresh()->load(['patient.user', 'medecin.user', 'medicaments'])
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $user = $request->user();
        if (!$user->hasRole('pharmacien')) {
            return response()->json(['message' => 'Forbidden: Only pharmaciens can validate/reject ordonnances'], 403);
        }
        $ordonnance = Ordonnance::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:validated,rejected,dispensed',
            'detail' => 'sometimes|string|max:500',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json([
                'status' => 'error',
                'message' => $errors[0] ?? 'Validation error.',
                'errors' => $validator->errors()
            ], 422);
        }
        $ordonnance->status = $request->status;
        if ($request->has('detail')) {
            $ordonnance->detail = $request->detail;
        }
        if ($request->status === 'validated') {
            $pharmacien = $user->pharmacien;
            if ($pharmacien) {
                $ordonnance->validated_by_pharmacie_id = $pharmacien->id;
            }
        }
        $ordonnance->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Ordonnance status updated',
            'data' => $ordonnance->fresh()
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $ordonnance = Ordonnance::findOrFail($id);
        $user = $request->user();

        if (
            !$user->hasAnyRole(['medecin', 'admin']) ||
            ($user->hasRole('medecin') && $ordonnance->medecin_id !== $user->medecin->id)
        ) {
            return response()->json(['message' => 'Forbidden: You can only delete your own ordonnances'], 403);
        }

        $ordonnance->medicaments()->detach();
        $ordonnance->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Ordonnance deleted successfully'
        ]);
    }

    public function ajouterMedicament(Request $request, $id)
    {
        $ordonnance = Ordonnance::findOrFail($id);
        $user = $request->user();

        if (
            !$user->hasAnyRole(['medecin', 'admin']) ||
            ($user->hasRole('medecin') && $ordonnance->medecin_id !== $user->medecin->id)
        ) {
            return response()->json(['message' => 'Forbidden: You can only modify your own ordonnances'], 403);
        }

        $validator = Validator::make($request->all(), [
            'medicament_id' => 'required|exists:medicaments,id',
            'quantite' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json([
                'status' => 'error',
                'message' => $errors[0] ?? 'Validation error.',
                'errors' => $validator->errors()
            ], 422);
        }

        $ordonnance->medicaments()->attach($request->medicament_id, [
            'quantite' => $request->quantite
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Medicament added to ordonnance successfully',
            'data' => $ordonnance->fresh()->load(['patient.user', 'medecin.user', 'medicaments'])
        ]);
    }

    public function validatedByPharmacien(Request $request)
    {
        $user = $request->user();
        if (!$user->hasRole('pharmacien')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $pharmacien = $user->pharmacien;
        if (!$pharmacien) {
            return response()->json(['message' => 'Pharmacien profile not found'], 404);
        }
        $ordonnances = \App\Models\Ordonnance::where('status', 'validated')
            ->where('validated_by_pharmacie_id', $pharmacien->id)
            ->with(['patient.user', 'medecin.user', 'medicaments', 'validatedByPharmacie'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $ordonnances
        ]);
    }

    public function downloadPdf(Request $request, $id)
    {
        $ordonnance = Ordonnance::with(['patient.user', 'medecin.user', 'medicaments'])->findOrFail($id);
        $user = $request->user();
        // Permission check (same as show)
        if ($user->hasRole('medecin')) {
            $medecin = $user->medecin;
            if (!$medecin || $ordonnance->medecin_id !== $medecin->id) {
                return response()->json(['message' => 'Forbidden: You can only view your own ordonnances'], 403);
            }
        } elseif ($user->hasRole('patient')) {
            $patient = $user->patient;
            if (!$patient || $ordonnance->patient_id !== $patient->id) {
                return response()->json(['message' => 'Forbidden: You can only view your own ordonnances'], 403);
            }
        } elseif (!$user->hasRole('admin')) {
            return response()->json(['message' => 'Forbidden: Access denied'], 403);
        }
        // Generate PDF
        $pdf = Pdf::loadView('ordonnance_pdf', ['ordonnance' => $ordonnance]);
        $filename = 'ordonnance_' . $ordonnance->id . '.pdf';
        return $pdf->download($filename);
    }

    public function downloadBulkPdf(Request $request)
    {
        $ids = $request->input('ordonnance_ids', []);
        if (!is_array($ids) || empty($ids)) {
            return response()->json(['message' => 'No ordonnances selected.'], 400);
        }
        $user = $request->user();
        $ordonnances = \App\Models\Ordonnance::with(['patient.user', 'medecin.user', 'medicaments'])
            ->whereIn('id', $ids)
            ->get();
        // Permission check for each ordonnance
        foreach ($ordonnances as $ordonnance) {
            if ($user->hasRole('medecin')) {
                $medecin = $user->medecin;
                if (!$medecin || $ordonnance->medecin_id !== $medecin->id) {
                    return response()->json(['message' => 'Forbidden: You can only view your own ordonnances'], 403);
                }
            } elseif ($user->hasRole('patient')) {
                $patient = $user->patient;
                if (!$patient || $ordonnance->patient_id !== $patient->id) {
                    return response()->json(['message' => 'Forbidden: You can only view your own ordonnances'], 403);
                }
            } elseif (!$user->hasRole('admin')) {
                return response()->json(['message' => 'Forbidden: Access denied'], 403);
            }
        }
        // Generate PDF with each ordonnance on a new page
        $pdf = app('dompdf.wrapper');
        $html = '';
        foreach ($ordonnances as $i => $ordonnance) {
            if ($i > 0) {
                $html .= '<div style="page-break-after: always;"></div>';
            }
            $html .= view('ordonnance_pdf', ['ordonnance' => $ordonnance])->render();
        }
        $pdf->loadHTML($html);
        $filename = 'ordonnances_bulk.pdf';
        return $pdf->download($filename);
    }
}
