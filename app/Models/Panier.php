<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panier extends Model
{
    use HasFactory;

    // Nom de la table
    protected $table = 'paniers';

    // Champs remplissables
    protected $fillable = [
        'status',
        'total',
        'id_utilisateur',
        'adresse_livraison_id',
    ];

    /* =======================
     |  Relations
     |======================= */

    // 🔹 L'utilisateur qui possède le panier
    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'id_utilisateur', 'id');
    }

    // 🔹 Les puzzles contenus dans le panier
    public function puzzles()
    {
        return $this->belongsToMany(Puzzle::class, 'appartient', 'id_Panier', 'id_Puzzle')
                    ->withPivot('quantite');
    }

    // 🔹 L'adresse de livraison associée au panier
    public function adresseLivraison()
    {
        return $this->belongsTo(Adresse::class, 'adresse_livraison_id', 'id');
    }

    /* =======================
     |  Méthodes utilitaires
     |======================= */

    // Exemple : calcul automatique du total
    public function calculerTotal()
    {
        return $this->puzzles->sum(fn($p) => $p->pivot->quantite * $p->prix);
    }
}
