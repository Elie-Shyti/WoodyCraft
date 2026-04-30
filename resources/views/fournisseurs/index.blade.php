<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Fournisseurs
        </h2>
    </x-slot>

    <div class="container mx-auto py-6">
        @if (session()->has('message'))
            <div class="mb-4 text-sm text-green-600">
                {{ session('message') }}
            </div>
        @endif

        <div class="mb-4">
            <a href="{{ route('fournisseurs.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-md text-sm hover:bg-gray-700">
                + Nouveau fournisseur
            </a>
        </div>

        <div class="overflow-x-auto bg-white shadow border-b border-gray-200 rounded-md">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-xs text-gray-500 text-left">#</th>
                        <th class="px-4 py-2 text-xs text-gray-500 text-left">Nom</th>
                        <th class="px-4 py-2 text-xs text-gray-500 text-left">Puzzles</th>
                        <th class="px-4 py-2 text-xs text-gray-500" colspan="2">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($fournisseurs as $fournisseur)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $fournisseur->id }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $fournisseur->nom }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $fournisseur->puzzles_count }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('fournisseurs.edit', $fournisseur) }}"
                                   class="inline-flex items-center px-2 py-1 bg-gray-800 text-white rounded-md text-xs">
                                    Modifier
                                </a>
                            </td>
                            <td class="px-4 py-3">
                                <form action="{{ route('fournisseurs.destroy', $fournisseur) }}" method="POST"
                                      onsubmit="return confirm('Supprimer ce fournisseur ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center px-2 py-1 bg-red-600 text-white rounded-md text-xs">
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-4 text-sm text-gray-500" colspan="5">Aucun fournisseur.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
