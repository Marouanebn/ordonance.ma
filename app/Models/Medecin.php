<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medecin extends Model
{
    //
    protected $fillable = ['user_id', 'nom_complet', 'telephone', 'numero_cnom', 'specialite', 'adresse_cabinet', 'ville', 'photo_profil', 'statut'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ordonnances()
    {
        return $this->hasMany(Ordonnance::class);
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }
}
