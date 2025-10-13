<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // On pointe vers la table existante
    protected $table = 'paniers';

    protected $fillable = ['id_utilisateur','status','total','adresse_livraison_id'];

    public function user()            { return $this->belongsTo(\App\Models\User::class, 'id_utilisateur'); }
    public function adresseLivraison(){ return $this->belongsTo(\App\Models\Adresse::class, 'adresse_livraison_id'); }
}
