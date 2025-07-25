<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordonnance extends Model
{
    //
    protected $fillable = [
        'patient_id',
        'medecin_id',
        'date_prescription',
        'detail',
        'status', // possible values: active, validated, rejected, dispensed
        'validated_by_pharmacie_id',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function medecin()
    {
        return $this->belongsTo(Medecin::class);
    }

    public function medicaments()
    {
        return $this->belongsToMany(Medicament::class)
            ->withPivot('quantite');
    }

    public function validatedByPharmacie()
    {
        return $this->belongsTo(Pharmacien::class, 'validated_by_pharmacie_id');
    }
}
