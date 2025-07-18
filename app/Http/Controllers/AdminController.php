<?php

namespace App\Http\Controllers;

use App\Models\Medecin;
use App\Models\Patient;
use App\Models\Pharmacien;
use App\Models\Ordonnance;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
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
            ->get()
            ->map(function ($row) {
                return [
                    'month' => $row->month,
                    'count' => $row->count
                ];
            });

        // Pie chart: repartition by laboratoire (example: count demandes per lab)
        $pieChart = \App\Models\Laboratoire::withCount('demandes')->get()
            ->map(function ($lab) {
                return [
                    'label' => $lab->nom_laboratoire ?? $lab->id,
                    'count' => $lab->demandes_count
                ];
            });

        return view('admin.dashboard', [
            'totalPatients' => $totalPatients,
            'totalMedecins' => $totalMedecins,
            'totalPharmacies' => $totalPharmacies,
            'totalOrdonnances' => $totalOrdonnances,
            'lineChart' => $lineChart,
            'pieChart' => $pieChart,
        ]);
    }

    public function medecins()
    {
        $medecins = Medecin::with('user')->get();
        return view('admin.medecins', compact('medecins'));
    }

    public function patients()
    {
        $patients = Patient::with('user')->get();
        return view('admin.patients', compact('patients'));
    }

    public function pharmacies()
    {
        $pharmacies = Pharmacien::with('user')->get();
        return view('admin.pharmacies', compact('pharmacies'));
    }

    public function ordonnances()
    {
        $ordonnances = Ordonnance::with(['patient.user', 'medecin.user'])->get();
        return view('admin.ordonnances', compact('ordonnances'));
    }

    public function edit($entity, $id)
    {
        switch ($entity) {
            case 'medecins':
                $medecin = \App\Models\Medecin::with('user')->findOrFail($id);
                return view('admin.edit_medecin', compact('medecin'));
            case 'patients':
                $patient = \App\Models\Patient::with('user')->findOrFail($id);
                return view('admin.edit_patient', compact('patient'));
            case 'pharmacies':
                $pharmacy = \App\Models\Pharmacien::with('user')->findOrFail($id);
                return view('admin.edit_pharmacy', compact('pharmacy'));
            case 'ordonnances':
                $ordonnance = \App\Models\Ordonnance::with(['patient.user', 'medecin.user', 'medicaments'])->findOrFail($id);
                return view('admin.edit_ordonnance', compact('ordonnance'));
            default:
                abort(404);
        }
    }

    public function update($entity, $id, \Illuminate\Http\Request $request)
    {
        switch ($entity) {
            case 'medecins':
                $medecin = \App\Models\Medecin::findOrFail($id);
                $medecin->update($request->except(['_token', '_method']));
                if ($medecin->user) {
                    $medecin->user->update($request->only(['name', 'email']));
                }
                return redirect()->route('admin.medecins')->with('success', 'Medecin updated successfully.');
            case 'patients':
                $patient = \App\Models\Patient::findOrFail($id);
                $patient->update($request->except(['_token', '_method']));
                if ($patient->user) {
                    $patient->user->update($request->only(['name', 'email']));
                }
                return redirect()->route('admin.patients')->with('success', 'Patient updated successfully.');
            case 'pharmacies':
                $pharmacy = \App\Models\Pharmacien::findOrFail($id);
                $pharmacy->update($request->except(['_token', '_method']));
                if ($pharmacy->user) {
                    $pharmacy->user->update($request->only(['name', 'email']));
                }
                return redirect()->route('admin.pharmacies')->with('success', 'Pharmacy updated successfully.');
            case 'ordonnances':
                $ordonnance = \App\Models\Ordonnance::findOrFail($id);
                $ordonnance->update($request->except(['_token', '_method']));
                return redirect()->route('admin.ordonnances')->with('success', 'Ordonnance updated successfully.');
            default:
                abort(404);
        }
    }
}
