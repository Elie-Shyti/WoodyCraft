<!-- resources/views/components/category-button.blade.php -->

<button 
    class="bg-blue-500 text-white py-2 px-4 rounded-md"
    id="categoryButton"
>
    Catégories
</button>

<!-- Affichage des catégories -->
<div id="categoryList" class="mt-4 hidden">
    <h3 class="text-lg font-semibold">Liste des Catégories</h3>

    <!-- Vérifier si des catégories existent -->
    @if($categories->isEmpty())
        <p>Aucune catégorie disponible.</p>
    @else
        <ul class="list-disc pl-5">
            <!-- Boucle à travers toutes les catégories -->
            @foreach($categories as $category)
                <li>{{ $category->nom }}</li>
            @endforeach
        </ul>
    @endif
</div>

<!-- Script pour afficher/cacher la liste des catégories -->
<script>
    document.getElementById('categoryButton').addEventListener('click', function() {
        var categoryList = document.getElementById('categoryList');
        categoryList.classList.toggle('hidden'); // Alterne entre afficher et masquer
    });
</script>
