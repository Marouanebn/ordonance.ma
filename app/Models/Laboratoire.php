<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laboratoire extends Model
{
    //
    protected $fillable = [
        'user_id',
        'nom_responsable',
        'nom_laboratoire',
        'telephone',
        'adresse',
        'ville',
        'numero_autorisation',
        'statut',
        'piece_identite_recto',
        'piece_identite_verso',
        'diplome',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function demandes()
    {
        return $this->hasMany(DemandeLaboratoire::class);
    }
}
