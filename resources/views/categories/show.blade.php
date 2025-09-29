<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Puzzles de la catégorie : ') }} {{ $category->nom }}
        </h2>
    </x-slot>

    <div class="container mx-auto">
        @if (session()->has('message'))
            <div class="mt-3 mb-4 text-sm text-green-600">
                {{ session('message') }}
            </div>
        @endif

        <div class="overflow-x-auto border-b border-gray-200 shadow pt-6 bg-white">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-2 py-2 text-xs text-gray-500">#</th>
                        <th class="px-2 py-2 text-xs text-gray-500">Nom du puzzle</th>
                        <th class="px-2 py-2 text-xs text-gray-500">Prix</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse ($puzzles as $puzzle)
                        <tr class="whitespace-nowrap">
                            <td class="px-4 py-4 text-sm text-gray-500">{{ $puzzle->id }}</td>
                            <td class="px-4 py-4">{{ $puzzle->nom }}</td>
                            <td class="px-4 py-4">{{ $puzzle->prix }} €</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-4 text-sm text-gray-500" colspan="3">Aucun puzzle pour cette catégorie.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
