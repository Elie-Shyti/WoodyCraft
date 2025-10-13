<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Adresse extends Model
{
    use HasFactory;

    protected $table = 'adresse'; // table au singulier

    protected $fillable = [
        'id_utilisateur', // FK vers users.id
        'numero',
        'rue',
        'ville',
        'cp',
        'pays',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_utilisateur', 'id');
    }
}
