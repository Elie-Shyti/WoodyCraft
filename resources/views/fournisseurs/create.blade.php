<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nouveau fournisseur
        </h2>
    </x-slot>

    <div class="max-w-xl mx-auto py-8 px-4">
        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm p-6">
            <form action="{{ route('fournisseurs.store') }}" method="POST">
                @csrf

                <div>
                    <x-input-label for="nom" :value="__('Nom du fournisseur')" />
                    <x-text-input id="nom" name="nom" type="text" class="block mt-1 w-full"
                                  :value="old('nom')" required autofocus />
                    <x-input-error :messages="$errors->get('nom')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-6 gap-3">
                    <a href="{{ route('fournisseurs.index') }}" class="text-sm text-gray-600 underline">Annuler</a>
                    <x-primary-button>Créer</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
