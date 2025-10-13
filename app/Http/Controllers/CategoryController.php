<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }
    
    public function show(Category $category)
    {
      // Vérifiez que la relation est bien définie et récupérez les puzzles
      $puzzles = $category->puzzles; // Récupère les puzzles associés à cette catégorie
     
      // Si la variable $puzzles est null ou vide, vous pouvez la forcer à être une collection vide
      if (is_null($puzzles)) {
          $puzzles = collect(); // Créer une collection vide si aucune puzzle n'est trouvé
      }
     
      // Retourner la vue avec la catégorie et les puzzles associés
      return view('categories.show', compact('category', 'puzzles'));
    }
}