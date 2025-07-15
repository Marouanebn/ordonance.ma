<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicament extends Model
{
    //
    protected $fillable = ['nom', 'quantite', 'disponible'];

    public function ordonnances()
    {
        return $this->belongsToMany(Ordonnance::class)
            ->withPivot('quantite');
    }

    public function demandesLaboratoire()
    {
        return $this->belongsToMany(DemandeLaboratoire::class, 'demande_laboratoire_medicament')
            ->withPivot('quantite');
    }
}
