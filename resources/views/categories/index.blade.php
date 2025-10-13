<x-app-layout>
    {{-- HERO --}}
    <section class="relative h-72 sm:h-80 md:h-96 w-full overflow-hidden">
        <img src="{{ asset('images/hero-forest.jpg') }}" alt="" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative h-full flex flex-col items-center justify-center text-center text-white px-4">
            <h1 class="text-4xl sm:text-5xl font-extrabold drop-shadow">WoodyCraft</h1>
            <p class="mt-3 text-base sm:text-lg opacity-90">3D puzzles that combine passion and creation</p>
        </div>
    </section>

    {{-- LISTE CATEGORIES --}}
    <div class="bg-gray-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h2 class="mb-4 text-lg font-semibold text-gray-800">categorys</h2>

            <div class="space-y-4">
                @forelse($categories as $category)
                    <a href="{{ route('categories.show', $category) }}"
                       class="block bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md hover:border-gray-300 transition">
                        <div class="p-4 flex items-start gap-4">
                            @php
                                // Image : adapte selon ton modèle (image_url, cover, etc.)
                                $img = $category->image_url
                                    ?? (isset($category->image_path) ? Storage::url($category->image_path) : null);
                            @endphp

                            <div class="w-24 h-24 flex-shrink-0 rounded-md overflow-hidden bg-gray-100">
                                @if($img)
                                    <img src="{{ $img }}" alt="{{ $category->nom ?? $category->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    {{-- Placeholder si pas d'image --}}
                                    <div class="w-full h-full grid place-items-center text-gray-400 text-xs">No image</div>
                                @endif
                            </div>

                            <div class="flex-1">
                                <h3 class="text-sm sm:text-base font-semibold text-gray-900">
                                    {{ $category->nom ?? $category->name }}
                                </h3>
                                <p class="mt-1 text-xs sm:text-sm text-gray-500 leading-snug">
                                    {{ $category->description ?? '—' }}
                                </p>
                            </div>

                            <div class="hidden sm:flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-300"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m9 18 6-6-6-6"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-gray-500 text-sm">Aucune catégorie pour le moment.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
