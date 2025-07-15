<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'user_id',
        'nom_complet',
        'telephone',
        'email',
        'date_naissance',
        'adresse',
        'ville',
        'genre',
        'numero_securite_sociale',
        'antecedents_medicaux',
        'allergies'
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ordonnances()
    {
        return $this->hasMany(Ordonnance::class);
    }

    public function medecin()
    {
        return $this->belongsTo(Medecin::class);
    }
}
