{{-- resources/views/cart/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h1 class="font-extrabold text-3xl text-gray-900 dark:text-gray-100">Cart</h1>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if (session('message'))
            <div class="mb-4 rounded-md bg-green-50 p-4 text-green-700">
                {{ session('message') }}
            </div>
        @endif

        @php
            // Sécurité si le contrôleur n'a pas calculé le total
            $computedTotal = isset($total)
                ? $total
                : collect($items ?? [])->sum(fn($it) => ($it['price'] ?? 0) * ($it['qty'] ?? 1));
        @endphp

        @if (empty($items) || count($items) === 0)
            {{-- État : panier vide --}}
            <div class="text-center py-24 bg-white dark:bg-gray-800 rounded-2xl shadow-sm">
                <p class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Votre panier est vide</p>
                <p class="mt-2 text-gray-500">Ajoutez des puzzles pour continuer.</p>
                <a href="{{ route('categories.index') }}"
                   class="mt-6 inline-flex items-center rounded-xl bg-black px-5 py-3 text-white hover:bg-gray-800">
                    Explorer les catégories
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Liste des produits --}}
                <div class="lg:col-span-2 space-y-6">
                    @foreach ($items as $item)
                        <div class="flex gap-4 bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4">
                            {{-- Image --}}
                            <div class="shrink-0">
                                <img
                                    src="{{ Str::startsWith($item['image'] ?? '', ['http://','https://']) ? $item['image'] : asset('storage/'.($item['image'] ?? '')) }}"
                                    alt="{{ $item['name'] ?? 'Puzzle' }}"
                                    class="h-24 w-24 object-cover rounded-xl ring-1 ring-gray-200"
                                    onerror="this.src='{{ asset('images/placeholder.png') }}'"
                                >
                            </div>

                            {{-- Infos --}}
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100 truncate">
                                    {{ $item['name'] ?? 'Puzzle' }}
                                </h3>
                                <div class="mt-1 text-sm text-gray-500">
                                    {{ number_format($item['price'] ?? 0, 2) }} $
                                </div>

                                {{-- Quantité + sous-total --}}
                                <div class="mt-3 flex items-center gap-4">
                                    {{-- Stepper quantité : - / valeur / +  --}}
                                    <div class="inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                                        {{-- Minus --}}
                                        <form method="POST" action="{{ route('cart.update', $item['id']) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="qty" value="{{ max(1, ($item['qty'] ?? 1) - 1) }}">
                                            <button class="px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-700" title="Diminuer">
                                                &minus;
                                            </button>
                                        </form>

                                        <div class="px-3 py-2 text-sm min-w-10 text-center">
                                            {{ $item['qty'] ?? 1 }}
                                        </div>

                                        {{-- Plus --}}
                                        <form method="POST" action="{{ route('cart.update', $item['id']) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="qty" value="{{ ($item['qty'] ?? 1) + 1 }}">
                                            <button class="px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-700" title="Augmenter">
                                                +
                                            </button>
                                        </form>
                                    </div>

                                    <div class="ml-auto text-right">
                                        <p class="text-sm text-gray-500">Sous-total</p>
                                        <p class="font-semibold text-gray-900 dark:text-gray-100">
                                            {{ number_format(($item['price'] ?? 0) * ($item['qty'] ?? 1), 2) }} $
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Supprimer --}}
                            <div class="self-start">
                                <form method="POST" action="{{ route('cart.destroy', $item['id']) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700" title="Retirer">
                                        {{-- Icône poubelle --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M6 7h12m-9 0V5a2 2 0 0 1 2-2h0a2 2 0 0 1 2 2v2m-7 0 1 12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-12" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach

                    {{-- Lien continuer --}}
                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('categories.index') }}" class="text-sm text-gray-600 hover:underline">
                            ← Continuer mes achats
                        </a>
                        <form method="POST" action="{{ route('cart.destroy', 'all') }}">
                            @csrf
                            @method('DELETE')
                            <button class="text-sm text-gray-500 hover:text-red-600" title="Vider le panier">
                                Vider le panier
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Résumé / Paiement --}}
                <aside class="lg:col-span-1">
                    <div class="lg:sticky lg:top-20 bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
                        <h2 class="font-bold text-2xl text-gray-900 dark:text-gray-100">Total</h2>

                        {{-- Code promo (optionnel) --}}
                        <form method="POST" action="{{ route('cart.applyCoupon') }}" class="mt-4">
                            @csrf
                            <label for="coupon" class="block text-sm text-gray-600">Code promo</label>
                            <div class="mt-1 flex">
                                <input id="coupon" name="code" type="text"
                                       class="w-full rounded-l-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900"
                                       placeholder="WOODY10">
                                <button class="rounded-r-xl bg-gray-900 px-4 py-2 text-white hover:bg-black">
                                    Appliquer
                                </button>
                            </div>
                        </form>

                        <dl class="mt-6 space-y-2 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Sous-total</dt>
                                <dd class="font-medium">{{ number_format($computedTotal, 2) }} $</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Livraison</dt>
                                <dd class="font-medium">Offerte dès 100 $</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600">TVA</dt>
                                <dd class="font-medium">Incluse</dd>
                            </div>
                            <div class="pt-2 border-t border-gray-200 dark:border-gray-700 flex justify-between text-lg">
                                <dt class="font-semibold">Total</dt>
                                <dd class="font-bold">{{ number_format($computedTotal, 2) }} $</dd>
                            </div>
                        </dl>

                        {{-- Bouton continuer / paiement --}}
                        <div class="mt-6 space-y-3">
                            <a href="{{ route('checkout.address.index') }}"
                               class="w-full inline-flex items-center justify-center rounded-xl bg-black px-5 py-3 text-white hover:bg-gray-800">
                                Aller au paiement
                            </a>
                            <p class="text-xs text-gray-500">
                                Paiement sécurisé • PayPal • Carte • Chèque
                            </p>
                        </div>
                    </div>
                </aside>
            </div>
        @endif
    </div>
</x-app-layout>
