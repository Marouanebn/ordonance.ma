<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandeLaboratoire extends Model
{
    //
    protected $fillable = ['pharmacien_id', 'laboratoire_id', 'date_demande', 'statut'];

    public function pharmacien()
    {
        return $this->belongsTo(Pharmacien::class);
    }

    public function laboratoire()
    {
        return $this->belongsTo(Laboratoire::class);
    }

    public function medicaments()
    {
        return $this->belongsToMany(Medicament::class, 'demande_laboratoire_medicament')
            ->withPivot('quantite');
    }
}
