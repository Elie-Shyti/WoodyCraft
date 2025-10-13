<x-app-layout>
    <div class="bg-gray-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            {{-- SECTION PRODUIT --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- IMAGE --}}
                    <div>
                        @php
                            $img = $puzzle->image_url
                                ?? (isset($puzzle->image_path) ? Storage::url($puzzle->image_path) : null);
                            $price = $puzzle->prix ?? $puzzle->price ?? 0;
                        @endphp

                        @if($img)
                            <img src="{{ $img }}" alt="{{ $puzzle->nom ?? $puzzle->name }}"
                                 class="w-full h-72 object-cover rounded-xl">
                        @else
                            <div class="w-full h-72 bg-gray-100 rounded-xl grid place-items-center text-gray-400 text-sm">
                                Aucune image
                            </div>
                        @endif
                    </div>

                    {{-- DETAILS --}}
                    <div class="flex flex-col">
                        <h1 class="text-2xl font-semibold text-gray-900">
                            {{ $puzzle->nom ?? $puzzle->name ?? 'Produit' }}
                        </h1>

                        @if($puzzle->category ?? false)
                            <span class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $puzzle->category->nom ?? $puzzle->category->name ?? 'Catégorie' }}
                            </span>
                        @endif

                        <div class="mt-3 text-3xl font-extrabold text-gray-900">
                            €{{ number_format($price, 2, ',', ' ') }}
                        </div>

                        <p class="mt-4 text-sm text-gray-600 leading-relaxed">
                            {{ $puzzle->description ?? 'Aucune description pour ce produit.' }}
                        </p>

                        {{-- AJOUTER AU PANIER --}}
                        <form method="POST" action="{{ route('cart.add', $puzzle) }}">
                            @csrf
                            <input type="hidden" name="qty" value="1">
                            <button
                                type="submit"
                                class="mt-3 inline-flex items-center rounded-xl bg-black px-4 py-2 text-white hover:bg-gray-800">
                                Ajouter au panier
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- SECTION AVIS --}}
            <div class="mt-10">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Avis des clients</h2>

                {{-- AFFICHAGE DES AVIS EXISTANTS --}}
                @forelse($puzzle->reviews as $review)
                    <div class="bg-white border border-gray-200 rounded-xl p-4 mb-3">
                        <div class="flex items-center gap-1 mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                     class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                     fill="currentColor">
                                    <path d="M10 15.27L16.18 19l-1.64-7.03L20 7.24l-7.19-.61L10 0 7.19 6.63 0 7.24l5.46 4.73L3.82 19z"/>
                                </svg>
                            @endfor
                        </div>
                        <p class="text-sm text-gray-800">{{ $review->comment }}</p>
                        <div class="mt-2 text-xs text-gray-500">
                            Posté par <strong>{{ $review->user->name }}</strong> le
                            {{ $review->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Aucun avis pour le moment. Soyez le premier à en laisser un !</p>
                @endforelse

                {{-- FORMULAIRE D’AVIS --}}
                @auth
                    @if(auth()->user()->hasPurchasedPuzzle($puzzle->id))
                        <div class="bg-white border border-gray-200 rounded-xl p-6 mt-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Laisser un avis</h3>

                            <form action="{{ route('reviews.store', $puzzle) }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Note :</label>
                                    <select name="rating" class="border-gray-300 rounded-md w-full">
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}">{{ $i }} ⭐</option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Commentaire :</label>
                                    <textarea name="comment" rows="3" class="w-full border-gray-300 rounded-md"></textarea>
                                </div>

                                <button type="submit"
                                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                    Publier mon avis
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mt-6 text-gray-500 text-sm">
                            Vous devez avoir commandé ce puzzle pour pouvoir laisser un avis.
                        </div>
                    @endif
                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mt-6 text-gray-500 text-sm">
                        Vous devez être connecté pour laisser un avis.
                    </div>
                @endauth
            </div>
        </div>
    </div>
</x-app-layout>
