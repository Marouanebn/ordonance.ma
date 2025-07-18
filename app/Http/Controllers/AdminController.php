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
        return view('admin.dashboard');
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
