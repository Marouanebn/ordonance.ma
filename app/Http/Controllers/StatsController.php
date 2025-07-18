<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function patient(Request $request)
    {
        $user = $request->user();
        $patient = $user->patient;
        if (!$patient) {
            return response()->json(['error' => 'Not a patient'], 403);
        }
        $totalOrdonnances = $patient->ordonnances()->count();
        $archivedOrdonnances = $patient->ordonnances()->where('status', 'archived')->count();
        $medecinsConsultes = $patient->ordonnances()->distinct('medecin_id')->count('medecin_id');
        $pharmaciesVisitees = 2; // TODO: Replace with real logic if available

        // Ordonnances by month (last 6 months)
        $ordonnancesByMonth = $patient->ordonnances()
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(fn($row) => ['month' => $row->month, 'count' => $row->count]);
        // Repartition by medecin
        $repartitionByMedecin = $patient->ordonnances()
            ->selectRaw('medecin_id, COUNT(*) as count')
            ->groupBy('medecin_id')
            ->with('medecin.user')
            ->get()
            ->map(fn($row) => [
                'label' => $row->medecin->user->name ?? 'Inconnu',
                'count' => $row->count
            ]);

        return response()->json([
            'totalOrdonnances' => $totalOrdonnances,
            'archivedOrdonnances' => $archivedOrdonnances,
            'medecinsConsultes' => $medecinsConsultes,
            'pharmaciesVisitees' => $pharmaciesVisitees,
            'charts' => [
                'ordonnancesByMonth' => $ordonnancesByMonth,
                'repartition' => $repartitionByMedecin,
            ]
        ]);
    }

    public function pharmacien(Request $request)
    {
        $user = $request->user();
        $pharmacien = $user->pharmacien;
        if (!$pharmacien) {
            return response()->json(['error' => 'Not a pharmacien'], 403);
        }
        $totalOrdonnancesTraitees = $pharmacien->ordonnances()->count();
        $ordonnancesValidees = $pharmacien->ordonnances()->where('status', 'validated')->count();
        $patientsServis = $pharmacien->ordonnances()->distinct('patient_id')->count('patient_id');
        $pharmaciesPartenaires = 4; // TODO: Replace with real logic if available

        // Ordonnances traitées by month (last 6 months)
        $ordonnancesByMonth = $pharmacien->ordonnances()
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(fn($row) => ['month' => $row->month, 'count' => $row->count]);
        // Repartition by patient
        $repartitionByPatient = $pharmacien->ordonnances()
            ->selectRaw('patient_id, COUNT(*) as count')
            ->groupBy('patient_id')
            ->with('patient.user')
            ->get()
            ->map(fn($row) => [
                'label' => $row->patient->user->name ?? 'Inconnu',
                'count' => $row->count
            ]);

        return response()->json([
            'totalOrdonnancesTraitees' => $totalOrdonnancesTraitees,
            'ordonnancesValidees' => $ordonnancesValidees,
            'patientsServis' => $patientsServis,
            'pharmaciesPartenaires' => $pharmaciesPartenaires,
            'charts' => [
                'ordonnancesByMonth' => $ordonnancesByMonth,
                'repartition' => $repartitionByPatient,
            ]
        ]);
    }

    public function medecin(Request $request)
    {
        $user = $request->user();
        $medecin = $user->medecin;
        if (!$medecin) {
            return response()->json(['error' => 'Not a medecin'], 403);
        }
        // Total ordonnances
        $totalOrdonnances = $medecin->ordonnances()->count();
        // Ordonnances archivées
        $archivedOrdonnances = $medecin->ordonnances()->where('status', 'archived')->count();
        // Nombre de patients distincts
        $nombrePatients = $medecin->ordonnances()->distinct('patient_id')->count('patient_id');
        // Ordonnances by month (last 6 months)
        $ordonnancesByMonth = $medecin->ordonnances()
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(fn($row) => ['month' => $row->month, 'count' => $row->count]);
        // Genre des patients (pie chart)
        $repartitionByGenre = $medecin->ordonnances()
            ->join('patients', 'ordonnances.patient_id', '=', 'patients.id')
            ->selectRaw('patients.genre as label, COUNT(*) as count')
            ->groupBy('patients.genre')
            ->get();
        return response()->json([
            'totalOrdonnances' => $totalOrdonnances,
            'archivedOrdonnances' => $archivedOrdonnances,
            'nombrePatients' => $nombrePatients,
            'charts' => [
                'ordonnancesByMonth' => $ordonnancesByMonth,
                'repartition' => $repartitionByGenre,
            ]
        ]);
    }

    public function admin()
    {
        $totalPatients = \App\Models\Patient::count();
        $totalMedecins = \App\Models\Medecin::count();
        $totalPharmacies = \App\Models\Pharmacien::count();
        $totalOrdonnances = \App\Models\Ordonnance::count();

        // Line chart: ordonnances per month (last 8 months)
        $lineChart = \App\Models\Ordonnance::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(8))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Pie chart: repartition by laboratoire (example: count demandes per lab)
        $pieChart = \App\Models\Laboratoire::withCount('demandes')->get()
            ->map(function ($lab) {
                return [
                    'label' => $lab->nom_laboratoire ?? $lab->id,
                    'count' => $lab->demandes_count
                ];
            });

        return response()->json([
            'totalPatients' => $totalPatients,
            'totalMedecins' => $totalMedecins,
            'totalPharmacies' => $totalPharmacies,
            'totalOrdonnances' => $totalOrdonnances,
            'charts' => [
                'line' => $lineChart,
                'pie' => $pieChart,
            ]
        ]);
    }
}
