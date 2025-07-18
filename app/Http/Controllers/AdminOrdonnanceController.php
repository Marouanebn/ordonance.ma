<?php

namespace App\Http\Controllers;

use App\Models\Ordonnance;
use Illuminate\Http\Request;

class AdminOrdonnanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Ordonnance::with(['patient.user', 'medecin.user', 'medicaments']);
        if ($search = $request->query('search')) {
            $query->whereHas('patient.user', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            })->orWhereHas('medecin.user', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }
        $sort = $request->query('sort', 'id');
        $direction = $request->query('direction', 'asc');
        $allowedSorts = ['id', 'date_prescription', 'status'];
        if (!in_array($sort, $allowedSorts)) $sort = 'id';
        if (!in_array($direction, ['asc', 'desc'])) $direction = 'asc';
        $query->orderBy($sort, $direction);
        $ordonnances = $query->paginate(10)->appends($request->query());
        return view('admin.ordonnances', compact('ordonnances'));
    }

    public function edit($id)
    {
        $ordonnance = Ordonnance::with(['patient.user', 'medecin.user', 'medicaments'])->findOrFail($id);
        return view('admin.edit_ordonnance', compact('ordonnance'));
    }

    public function update(Request $request, $id)
    {
        $ordonnance = Ordonnance::findOrFail($id);
        $ordonnance->update($request->except(['_token', '_method']));
        return redirect()->route('admin.ordonnances')->with('success', 'Ordonnance updated successfully.');
    }

    public function destroy($id)
    {
        $ordonnance = Ordonnance::findOrFail($id);
        $ordonnance->delete();
        return redirect()->route('admin.ordonnances')->with('success', 'Ordonnance deleted successfully.');
    }
}
