<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Puzzle;
use App\Models\Panier;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // ==========================
    //   PAGE PANIER (auth)
    // ==========================
    public function index()
    {
        $user = Auth::user();

        // Récupère le panier "open" de l'utilisateur (ou crée-le)
        $panier = Panier::firstOrCreate(
            [
                'id_utilisateur' => $user->id,
                'status' => 'open',
            ]
        );

        // Charge les puzzles associés
        $panier->load(['puzzles']);

        // Calcule le total
        $total = $panier->puzzles->sum(fn($p) => ($p->pivot->quantite ?? 1) * ($p->pivot->prix ?? $p->prix ?? 0));

        return view('cart.index_db', compact('panier', 'total'));
    }

    // ==========================
    //   AJOUTER AU PANIER
    // ==========================
    public function add(Request $request, Puzzle $puzzle)
    {
        $user = Auth::user();

        // On cherche le panier "open" du user (sinon on le crée)
        $panier = Panier::firstOrCreate(
            [
                'id_utilisateur' => $user->id,
                'status' => 'open',
            ]
        );

        $qty = max(1, (int) $request->input('qty', 1));
        $prix = $puzzle->prix ?? 0;

        // Vérifie si le puzzle est déjà dans le panier
        $existing = $panier->puzzles()->where('puzzles.id', $puzzle->id)->first();

        if ($existing) {
            // Met à jour la quantité
            $newQty = $existing->pivot->quantite + $qty;
            $panier->puzzles()->updateExistingPivot($puzzle->id, ['quantite' => $newQty, 'prix' => $prix]);
        } else {
            // Ajoute dans la table pivot
            $panier->puzzles()->attach($puzzle->id, ['quantite' => $qty, 'prix' => $prix]);
        }

        return back()->with('message', 'Article ajouté au panier.');
    }

    // ==========================
    //   METTRE À JOUR UNE LIGNE
    // ==========================
    public function update(Request $request, $puzzleId)
    {
        $user = Auth::user();

        $panier = Panier::where('id_utilisateur', $user->id)
                        ->where('status', 'open')
                        ->first();

        if ($panier) {
            $qty = max(1, (int) $request->input('qty', 1));
            $panier->puzzles()->updateExistingPivot($puzzleId, ['quantite' => $qty]);
        }

        return back()->with('message', 'Quantité mise à jour.');
    }

    // ==========================
    //   SUPPRIMER UNE LIGNE
    // ==========================
    public function destroy($puzzleId)
    {
        $user = Auth::user();

        $panier = Panier::where('id_utilisateur', $user->id)
                        ->where('status', 'open')
                        ->first();

        if ($panier) {
            $panier->puzzles()->detach($puzzleId);
        }

        return back()->with('message', 'Article retiré.');
    }
}
