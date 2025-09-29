<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Puzzle;
use App\Models\Category;


class PuzzleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $puzzles = Puzzle::all();
        return view('puzzles.index', compact('puzzles'));

        $categories = Category::all();
        return view('categories', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    $categories = Category::all();  
    
    return view('puzzles.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $date = $request->validate([
        'nom'         => 'required|max:100',
        'categorie'   => 'required|max:100',
        'category_id'=> 'required|max:100',
        'description' => 'required|max:500',
        'image'       => 'required|max:500',
        'prix'        => 'required|numeric|between:0,99.99',]);

        $puzzle = new Puzzle();
        $puzzle->nom = $request->nom;
        $puzzle->categorie = $request->categorie;
        $puzzle->category_id = $request->category_id;
        $puzzle->description = $request->description;
        $puzzle->image = $request->image;
        $puzzle->prix = $request->prix;
        $puzzle->save();
        return back()->with('message', "Le puzzle a bien été créé !");
    }

    /**
     * Display the specified resource.
     */
    public function show(Puzzle $puzzle)
    {
        return view('puzzles.show', compact('puzzle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Puzzle $puzzle)
    {
        return view('puzzles.edit', compact('puzzle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Puzzle $puzzle)
    {
        $data = $request->validate([
            'nom'         => 'required|max:100',
            'categorie'   => 'required|max:100',
            'description' => 'required|max:500',
            'image'       => 'required|max:500',
            'prix'        => 'required|numeric|between:0,99.99',]);
            
            $puzzle->nom=$request->nom;
            $puzzle->categorie=$request->categorie;
            $puzzle->description=$request->description;
            $puzzle->image=$request->image;
            $puzzle->prix=$request->prix;

            $puzzle->update($data);

            return redirect()
            ->route('puzzles.edit', $puzzle)
            ->with('message', 'Puzzle mis à jour !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Puzzle $puzzle)
    {
        $puzzle->delete();
        

        return redirect()
        ->route('puzzles.index')
        ->with('message', 'Le puzzle a bien été supprimé.');
    }
}
