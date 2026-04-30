<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fournisseur;

class FournisseurController extends Controller
{
    public function index()
    {
        $fournisseurs = Fournisseur::withCount('puzzles')->orderBy('nom')->get();
        return view('fournisseurs.index', compact('fournisseurs'));
    }

    public function create()
    {
        return view('fournisseurs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|max:150|unique:fournisseurs,nom',
        ]);

        Fournisseur::create(['nom' => $request->nom]);

        return redirect()->route('fournisseurs.index')
            ->with('message', 'Fournisseur créé avec succès !');
    }

    public function edit(Fournisseur $fournisseur)
    {
        return view('fournisseurs.edit', compact('fournisseur'));
    }

    public function update(Request $request, Fournisseur $fournisseur)
    {
        $request->validate([
            'nom' => 'required|max:150|unique:fournisseurs,nom,' . $fournisseur->id,
        ]);

        $fournisseur->update(['nom' => $request->nom]);

        return redirect()->route('fournisseurs.index')
            ->with('message', 'Fournisseur mis à jour !');
    }

    public function destroy(Fournisseur $fournisseur)
    {
        $fournisseur->delete();
        return redirect()->route('fournisseurs.index')
            ->with('message', 'Fournisseur supprimé.');
    }
}
