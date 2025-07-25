<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pharmacien extends Model
{
    //
    protected $fillable = [
        'user_id',
        'nom_pharmacie',
        'telephone',
        'adresse_pharmacie',
        'ville',
        'statut',
        'document_justificatif_url',
        'photo_url',
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
