<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Liste des puzzles') }}
        </h2>
    </x-slot>

    <div class="container mx-auto">
        @if (session()->has('message'))
            <div class="mt-3 mb-4 text-sm text-green-600">
                {{ session('message') }}
            </div>
        @endif

        {{-- Filtre par fournisseur --}}
        <div class="mt-4 mb-4">
            <form method="GET" action="{{ route('puzzles.index') }}" class="flex items-center gap-3">
                <label for="fournisseur_id" class="text-sm font-medium text-gray-700">Filtrer par fournisseur :</label>
                <select id="fournisseur_id" name="fournisseur_id"
                        class="border border-gray-300 rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400">
                    <option value="">— Tous les fournisseurs —</option>
                    @foreach ($fournisseurs as $f)
                        <option value="{{ $f->id }}" {{ request('fournisseur_id') == $f->id ? 'selected' : '' }}>
                            {{ $f->nom }}
                        </option>
                    @endforeach
                </select>
                <button type="submit"
                        class="px-3 py-1.5 bg-gray-800 text-white text-sm rounded-md hover:bg-gray-700">
                    Filtrer
                </button>
                @if(request('fournisseur_id'))
                    <a href="{{ route('puzzles.index') }}" class="text-sm text-gray-500 underline">Réinitialiser</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto border-b border-gray-200 shadow pt-4 bg-white">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-2 py-2 text-xs text-gray-500">#</th>
                        <th class="px-2 py-2 text-xs text-gray-500">Nom</th>
                        <th class="px-2 py-2 text-xs text-gray-500">Fournisseur</th>
                        <th class="px-2 py-2 text-xs text-gray-500" colspan="4">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse ($puzzles as $puzzle)
                        <tr class="whitespace-nowrap">
                            <td class="px-4 py-4 text-sm text-gray-500">{{ $puzzle->id }}</td>
                            <td class="px-4 py-4">{{ $puzzle->nom }}</td>
                            <td class="px-4 py-4 text-sm text-gray-600">
                                {{ $puzzle->fournisseur->nom ?? '—' }}
                            </td>

                            {{-- Show --}}
                            <td class="px-2 py-2">
                                <a href="{{ route('puzzles.show', $puzzle->id) }}"
                                   class="inline-flex items-center px-2 py-1 bg-gray-800 text-white rounded-md text-xs">
                                   Show
                                </a>
                            </td>

                            {{-- Edit --}}
                            <td class="px-2 py-2">
                                <a href="{{ route('puzzles.edit', $puzzle->id) }}"
                                   class="inline-flex items-center px-2 py-1 bg-gray-800 text-white rounded-md text-xs">
                                   Edit
                                </a>
                            </td>

                            {{-- Delete --}}
                            <td class="px-2 py-2">
                                <form action="{{ route('puzzles.destroy', $puzzle->id) }}" method="POST"
                                      onsubmit="return confirm('Supprimer ce puzzle ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center px-2 py-1 bg-red-600 text-white rounded-md text-xs">
                                        Delete
                                    </button>
                                </form>
                            </td>

                            {{-- Ajout au Panier --}}
                            <td class="px-2 py-2">
                                <form action="{{ route('cart.add', $puzzle) }}" method="POST" class="inline-flex items-center space-x-2">
                                    @csrf
                                    <input type="number" name="qty" value="1" min="1" class="w-16 border rounded px-2 py-1">
                                    <x-primary-button>Ajouter au panier</x-primary-button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-4 text-sm text-gray-500" colspan="7">Aucun puzzle.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
