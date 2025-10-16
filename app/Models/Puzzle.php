<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Puzzle extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'categorie',
        'description',
        'prix',
        'image',
    ];

    /**
     * Relation : un puzzle peut avoir plusieurs avis
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'puzzles_id');
    }

    public function paniers()
    {
        return $this->belongsToMany(Panier::class, 'appartient', 'id_Puzzle', 'id_Panier')
        ->withPivot('quantite', 'prix');
    }

}
