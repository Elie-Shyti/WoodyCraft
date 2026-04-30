<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Puzzle;
use App\Models\Category;
use App\Models\Fournisseur;


class PuzzleController extends Controller
{
    public function index(Request $request)
    {
        $fournisseurs = Fournisseur::orderBy('nom')->get();

        $query = Puzzle::with('fournisseur');

        if ($request->filled('fournisseur_id')) {
            $query->where('fournisseur_id', $request->fournisseur_id);
        }

        $puzzles = $query->get();

        return view('puzzles.index', compact('puzzles', 'fournisseurs'));
    }

    public function create()
    {
        $categories = Category::all();
        $fournisseurs = Fournisseur::orderBy('nom')->get();

        return view('puzzles.create', compact('categories', 'fournisseurs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom'            => 'required|max:100',
            'categorie'      => 'required|max:100',
            'category_id'    => 'required|exists:categories,id',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'description'    => 'required|max:500',
            'image'          => 'required|max:500',
            'prix'           => 'required|numeric|between:0,9999.99',
        ]);

        Puzzle::create($data);

        return back()->with('message', 'Le puzzle a bien été créé !');
    }

    public function show(Puzzle $puzzle)
    {
        $puzzle->load('fournisseur');

        if (!empty($puzzle->image_url)) {
            $image = $puzzle->image_url;
        } elseif (!empty($puzzle->image_path) && file_exists(public_path($puzzle->image_path))) {
            $image = asset($puzzle->image_path);
        } elseif (!empty($puzzle->image_path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($puzzle->image_path)) {
            $image = \Illuminate\Support\Facades\Storage::url($puzzle->image_path);
        } elseif (file_exists(public_path('images/produit.png'))) {
            $image = asset('images/produit.png');
        } else {
            $image = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';
        }

        return view('puzzles.show', compact('puzzle', 'image'));
    }

    public function edit(Puzzle $puzzle)
    {
        $categories = Category::all();
        $fournisseurs = Fournisseur::orderBy('nom')->get();

        return view('puzzles.edit', compact('puzzle', 'categories', 'fournisseurs'));
    }

    public function update(Request $request, Puzzle $puzzle)
    {
        $data = $request->validate([
            'nom'            => 'required|max:100',
            'categorie'      => 'required|max:100',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'description'    => 'required|max:500',
            'image'          => 'required|max:500',
            'prix'           => 'required|numeric|between:0,9999.99',
        ]);

        $puzzle->update($data);

        return redirect()
            ->route('puzzles.edit', $puzzle)
            ->with('message', 'Puzzle mis à jour !');
    }

    public function destroy(Puzzle $puzzle)
    {
        $puzzle->delete();
        return redirect()->route('puzzles.index')->with('message', 'Puzzle supprimé.');
    }
}
