<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panier extends Model
{
    use HasFactory;

    protected $table = 'paniers';

    protected $fillable = [
        'status',
        'total',
        'id_utilisateur',
        'adresse_livraison_id',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'id_utilisateur', 'id');
    }

    public function puzzles()
    {
        return $this->belongsToMany(Puzzle::class, 'appartient', 'id_Panier', 'id_Puzzle')
        ->withPivot('quantite', 'prix');
    }

    public function adresseLivraison()
    {
        return $this->belongsTo(Adresse::class, 'adresse_livraison_id', 'id');
    }
}
