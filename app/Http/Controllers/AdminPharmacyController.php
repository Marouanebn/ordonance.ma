<?php

namespace App\Http\Controllers;

use App\Models\Pharmacien;
use Illuminate\Http\Request;

class AdminPharmacyController extends Controller
{
    public function index(Request $request)
    {
        $query = Pharmacien::with('user');
        if ($search = $request->query('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }
        $sort = $request->query('sort', 'id');
        $direction = $request->query('direction', 'asc');
        $allowedSorts = ['name', 'email', 'nom_pharmacie', 'telephone', 'adresse_pharmacie', 'ville', 'statut', 'id'];
        if (!in_array($sort, $allowedSorts)) $sort = 'id';
        if (!in_array($direction, ['asc', 'desc'])) $direction = 'asc';
        if ($sort === 'name' || $sort === 'email') {
            $query->join('users', 'pharmaciens.user_id', '=', 'users.id')
                ->orderBy('users.' . $sort, $direction)
                ->select('pharmaciens.*');
        } else {
            $query->orderBy($sort, $direction);
        }
        $pharmacies = $query->paginate(10)->appends($request->query());
        return view('admin.pharmacies', compact('pharmacies'));
    }

    public function edit($id)
    {
        $pharmacy = Pharmacien::with('user')->findOrFail($id);
        return view('admin.edit_pharmacy', compact('pharmacy'));
    }

    public function update(Request $request, $id)
    {
        $pharmacy = Pharmacien::findOrFail($id);
        $pharmacy->update($request->except(['_token', '_method']));
        if ($pharmacy->user) {
            $pharmacy->user->update($request->only(['name', 'email']));
        }
        return redirect()->route('admin.pharmacies')->with('success', 'Pharmacy updated successfully.');
    }

    public function destroy($id)
    {
        $pharmacy = Pharmacien::findOrFail($id);
        if ($pharmacy->user) {
            $pharmacy->user->delete();
        }
        $pharmacy->delete();
        return redirect()->route('admin.pharmacies')->with('success', 'Pharmacy deleted successfully.');
    }
}
