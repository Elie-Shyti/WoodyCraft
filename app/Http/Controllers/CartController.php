<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Puzzle;
use App\Models\Panier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CartController extends Controller
{
    // ==========================
    //   PAGE PANIER (auth)
    // ==========================
    public function index()
    {
        $user = Auth::user();

        // Récupère ou crée le panier "open" du user
        $panier = Panier::firstOrCreate([
            'id_utilisateur' => $user->id,
            'status' => 'open',
        ]);

        // Recharge explicitement les puzzles (relation many-to-many)
        $panier->load('puzzles');

        // Construis un tableau d'items prêt à afficher, en résolvant l'image au rendu
        $items = $panier->puzzles->map(function ($p) {
            // Résolution de l'image : priorité au champ direct (image_url), puis image_path (storage/public ou public/), puis fallback
            if (!empty($p->image_url)) {
                $image = $p->image_url;
            } elseif (!empty($p->image_path) && file_exists(public_path($p->image_path))) {
                // cas où image_path est ex: "images/produit.png"
                $image = asset($p->image_path);
            } elseif (!empty($p->image_path) && Storage::disk('public')->exists($p->image_path)) {
                // cas où image_path est ex: "products/1.jpg" stocké dans storage/app/public
                $image = Storage::url($p->image_path);
            } else {
                $image = asset('images/produit.png'); // fallback
            }

            $price = $p->pivot->prix ?? $p->prix ?? 0;
            $qty = $p->pivot->quantite ?? 1;
            $subtotal = $price * $qty;

            return [
                'id' => $p->id,
                'name' => $p->nom ?? $p->name ?? 'Produit',
                'image' => $image,
                'price' => $price,
                'qty' => $qty,
                'subtotal' => $subtotal,
            ];
        })->toArray();

        // Calcul du total (recalculé à partir des items pour cohérence)
        $total = array_reduce($items, fn($acc, $it) => $acc + ($it['subtotal'] ?? 0), 0);

        // Passe $items et $total à la vue (et $panier si tu veux garder la référence)
        return view('cart.index_db', compact('panier', 'items', 'total'));
    }

    // ==========================
    //   AJOUTER AU PANIER
    // ==========================
    public function add(Request $request, Puzzle $puzzle)
    {
        $user = Auth::user();

        // Récupère ou crée le panier "open" du user
        $panier = Panier::firstOrCreate([
            'id_utilisateur' => $user->id,
            'status' => 'open',
        ]);

        $qty = max(1, (int) $request->input('qty', 1));
        $prix = $puzzle->prix ?? 0;

        // Vérifie si le puzzle existe déjà dans le panier
        $existing = $panier->puzzles()->where('puzzles.id', $puzzle->id)->first();

        if ($existing) {
            // S’il existe déjà → on met à jour la quantité et le prix
            $newQty = $existing->pivot->quantite + $qty;
            $panier->puzzles()->updateExistingPivot($puzzle->id, [
                'quantite' => $newQty,
                'prix' => $prix,
            ]);
        } else {
            // Sinon, on ajoute sans détacher le reste
            $panier->puzzles()->syncWithoutDetaching([
                $puzzle->id => ['quantite' => $qty, 'prix' => $prix],
            ]);
        }

        // Redirection vers la page du panier
        return redirect()->route('cart.index')->with('message', 'Article ajouté au panier.');
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
