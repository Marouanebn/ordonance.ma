<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class AdminPatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::with('user');
        if ($search = $request->query('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }
        $sort = $request->query('sort', 'id');
        $direction = $request->query('direction', 'asc');
        $allowedSorts = ['name', 'email', 'nom_complet', 'cin', 'telephone', 'date_naissance', 'genre', 'numero_securite_sociale', 'id'];
        if (!in_array($sort, $allowedSorts)) $sort = 'id';
        if (!in_array($direction, ['asc', 'desc'])) $direction = 'asc';
        if ($sort === 'name' || $sort === 'email') {
            $query->join('users', 'patients.user_id', '=', 'users.id')
                ->orderBy('users.' . $sort, $direction)
                ->select('patients.*');
        } else {
            $query->orderBy($sort, $direction);
        }
        $patients = $query->paginate(10)->appends($request->query());
        return view('admin.patients', compact('patients'));
    }

    public function edit($id)
    {
        $patient = Patient::with('user')->findOrFail($id);
        return view('admin.edit_patient', compact('patient'));
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);
        $patient->update($request->except(['_token', '_method']));
        if ($patient->user) {
            $patient->user->update($request->only(['name', 'email']));
        }
        return redirect()->route('admin.patients')->with('success', 'Patient updated successfully.');
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        if ($patient->user) {
            $patient->user->delete();
        }
        $patient->delete();
        return redirect()->route('admin.patients')->with('success', 'Patient deleted successfully.');
    }
}
