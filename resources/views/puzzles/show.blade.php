<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Afficher un puzzle') }}
        </h2>
    </x-slot>

    <x-puzzles-card>
        <h3 class="font-semibold text-xl text-gray-800">{{ __('Nom') }}</h3>
        <p>{{ $puzzle->nom }}</p>

        <h3 class="font-semibold text-xl text-gray-800 pt-2">{{ __('Catégorie') }}</h3>
        <p>{{ $puzzle->categorie }}</p>

        <h3 class="font-semibold text-xl text-gray-800 pt-2">{{ __('Description') }}</h3>
        <p>{{ $puzzle->description }}</p>

        <h3 class="font-semibold text-xl text-gray-800 pt-2">{{ __('Date de création') }}</h3>
        <p>{{ optional($puzzle->created_at)->format('d/m/Y') }}</p>

        @if(optional($puzzle->updated_at)->ne($puzzle->created_at))
            <h3 class="font-semibold text-xl text-gray-800 pt-2">{{ __('Dernière mise à jour') }}</h3>
            <p>{{ optional($puzzle->updated_at)->format('d/m/Y') }}</p>
        @endif
    </x-puzzles-card>
</x-app-layout>
