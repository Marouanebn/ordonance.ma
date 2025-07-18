<?php

namespace App\Http\Controllers;

use App\Models\Medecin;
use Illuminate\Http\Request;

class AdminMedecinController extends Controller
{
    public function index(Request $request)
    {
        $query = Medecin::with('user');
        if ($search = $request->query('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }
        $sort = $request->query('sort', 'id');
        $direction = $request->query('direction', 'asc');
        $allowedSorts = ['name', 'email', 'nom_complet', 'specialite', 'ville', 'statut', 'id'];
        if (!in_array($sort, $allowedSorts)) $sort = 'id';
        if (!in_array($direction, ['asc', 'desc'])) $direction = 'asc';
        if ($sort === 'name' || $sort === 'email') {
            $query->join('users', 'medecins.user_id', '=', 'users.id')
                ->orderBy('users.' . $sort, $direction)
                ->select('medecins.*');
        } else {
            $query->orderBy($sort, $direction);
        }
        $medecins = $query->paginate(10)->appends($request->query());
        return view('admin.medecins', compact('medecins'));
    }

    public function edit($id)
    {
        $medecin = Medecin::with('user')->findOrFail($id);
        return view('admin.edit_medecin', compact('medecin'));
    }

    public function update(Request $request, $id)
    {
        $medecin = Medecin::findOrFail($id);
        $medecin->update($request->except(['_token', '_method']));
        if ($medecin->user) {
            $medecin->user->update($request->only(['name', 'email']));
        }
        return redirect()->route('admin.medecins')->with('success', 'Medecin updated successfully.');
    }

    public function destroy($id)
    {
        $medecin = Medecin::findOrFail($id);
        if ($medecin->user) {
            $medecin->user->delete();
        }
        $medecin->delete();
        return redirect()->route('admin.medecins')->with('success', 'Medecin deleted successfully.');
    }
}
