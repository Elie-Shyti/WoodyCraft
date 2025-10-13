<x-app-layout>
    <div class="bg-gray-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h2 class="mb-6 text-lg font-semibold text-gray-800">
                {{ $category->nom ?? $category->name }}
            </h2>

            @if($puzzles->isEmpty())
                <p class="text-sm text-gray-500">Aucun puzzle pour cette catégorie.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach ($puzzles as $puzzle)
                        @php
                            // Image du puzzle (adapte aux champs de ton modèle)
                            $img = $puzzle->image_url
                                ?? (isset($puzzle->image_path) ? Storage::url($puzzle->image_path) : null);

                            // On rend les items 5 et 6 "larges" pour coller à la maquette
                            $isWide = $loop->iteration > 4 && $loop->iteration <= 6;
                            $price = $puzzle->prix ?? $puzzle->price ?? 0;
                        @endphp

                        <a href="{{ route('puzzles.show', $puzzle) }}"
                           class="group block bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md hover:border-gray-300 transition {{ $isWide ? 'sm:col-span-2' : '' }}">
                            @if($isWide)
                                {{-- Carte large (paysage) --}}
                                <div class="flex flex-col sm:flex-row">
                                    <div class="sm:w-1/2">
                                        @if($img)
                                            <img src="{{ $img }}" alt="{{ $puzzle->nom ?? $puzzle->name }}"
                                                 class="w-full h-56 object-cover rounded-t-xl sm:rounded-tr-none sm:rounded-l-xl">
                                        @else
                                            <div class="w-full h-56 bg-gray-100 rounded-t-xl sm:rounded-tr-none sm:rounded-l-xl grid place-items-center text-gray-400 text-xs">No image</div>
                                        @endif
                                    </div>
                                    <div class="p-4 sm:w-1/2">
                                        <h3 class="text-sm font-semibold text-gray-900 truncate">
                                            {{ $puzzle->nom ?? $puzzle->name ?? 'Text' }}
                                        </h3>
                                        <div class="mt-2 text-xs text-gray-500 line-clamp-2">
                                            {{ \Illuminate\Support\Str::limit($puzzle->description ?? 'Text', 90) }}
                                        </div>
                                        <div class="mt-3 font-semibold text-gray-900">${{ number_format($price, 0, '.', ' ') }}</div>
                                    </div>
                                </div>
                            @else
                                {{-- Petite carte (portrait) --}}
                                <div>
                                    @if($img)
                                        <img src="{{ $img }}" alt="{{ $puzzle->nom ?? $puzzle->name }}"
                                             class="w-full h-48 object-cover rounded-t-xl">
                                    @else
                                        <div class="w-full h-48 bg-gray-100 rounded-t-xl grid place-items-center text-gray-400 text-xs">No image</div>
                                    @endif
                                </div>
                                <div class="p-3">
                                    <h3 class="text-sm font-semibold text-gray-900 truncate">
                                        {{ $puzzle->nom ?? $puzzle->name ?? 'Text' }}
                                    </h3>
                                    <div class="mt-1 text-xs text-gray-500">Text</div>
                                    <div class="mt-2 font-semibold text-gray-900">${{ number_format($price, 0, '.', ' ') }}</div>
                                </div>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
