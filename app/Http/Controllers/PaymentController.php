<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Panier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        return $this->show($request);
    }

    public function show(Request $request)
    {
        $address = session('checkout.address');
        return view('checkout.payment', compact('address'));
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => ['required', 'in:paypal,cheque,card'],
            'card_name'      => ['exclude_unless:payment_method,card', 'required', 'string', 'max:120'],
            'card_number'    => ['exclude_unless:payment_method,card', 'required', 'string', 'regex:/^\d{12,19}$/'],
            'card_exp'       => ['exclude_unless:payment_method,card', 'required', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
            'card_cvc'       => ['exclude_unless:payment_method,card', 'required', 'string', 'regex:/^\d{3,4}$/'],
        ]);

        $method = $validated['payment_method'];
        $user   = $request->user();

        // Récupère le panier en cours
        $panier = Panier::where('id_utilisateur', $user->id)
            ->when(
                Schema::hasColumn('paniers', 'status'),
                fn($q) => $q->where('status', 'open')
            )
            ->latest('id')
            ->first();

        // Si un panier est trouvé, on le marque comme "completed"
        if ($panier) {
            $panier->status = 'completed';
            $panier->save();
        }

        // --- PAYPAL ---
        if ($method === 'paypal') {
            return redirect()->away('https://www.paypal.com/fr/home');
        }

        // --- CHEQUE ---
        if ($method === 'cheque') {
            session()->put('checkout.invoice_user_id', $user->id);
            return redirect()->route('checkout.invoice');
        }

        // --- CARTE BANCAIRE ---
        session()->put('checkout.payment_choice', [
            'method' => 'card',
            'card'   => [
                'name'  => $request->card_name,
                'last4' => substr(preg_replace('/\D/', '', $request->card_number), -4),
                'exp'   => $request->card_exp,
            ],
        ]);

        return redirect()
            ->route('checkout.success')
            ->with('showReviewPopup', true)
            ->with('message', 'Paiement par carte enregistré avec succès !');
    }

    public function confirm()
    {
        $address = session('checkout.address');
        $payment = session('checkout.payment_choice');
        return view('checkout.confirmation', compact('address', 'payment'));
    }

    public function success()
    {
        return view('checkout.success');
    }

    // ✅ Génération du PDF et redirection après
    public function invoice()
    {
        $userId = session('checkout.invoice_user_id');
        $user = \App\Models\User::find($userId);
    
        if (!$user) {
            return redirect()->route('checkout.success');
        }
    
        $panier = Panier::where('id_utilisateur', $user->id)
            ->latest('id')
            ->with(['puzzles' => fn($q) => $q->withPivot(['quantite','prix'])])
            ->first();

        // 🟢 On vérifie et on marque bien comme "completed"
        if ($panier && $panier->status === 'open') {
            $panier->status = 'completed';
            $panier->save();
        }
    
        $lines = $panier
            ? $panier->puzzles->map(fn($p) => [
                'name'       => $p->nom ?? $p->name ?? 'Puzzle',
                'quantity'   => (int)($p->pivot->quantite ?? 1),
                'unit_price' => (float)($p->pivot->prix ?? $p->prix ?? 0),
                'line_total' => (int)($p->pivot->quantite ?? 1) * (float)($p->pivot->prix ?? $p->prix ?? 0),
            ])
            : collect();

        $total = $lines->sum('line_total');
        $invoiceNo = 'FAC-' . now()->format('Ymd-His') . '-' . $user->id;
    
        $cheque_to = config('app.cheque_to', 'WoodyCraft');
        $cheque_address = config('app.cheque_address', "— Adresse postale de l'entreprise —");
    
        $pdf = Pdf::loadView('pdf.facture-cheque', [
            'user' => $user,
            'lines' => $lines,
            'total' => $total,
            'invoiceNo' => $invoiceNo,
            'generated_at' => now(),
            'cheque_to' => $cheque_to,
            'cheque_address' => $cheque_address,
        ])->setPaper('a4');
    
        $fileName = "facture-cheque-{$invoiceNo}.pdf";
        $pdfData = base64_encode($pdf->output());
    
        return view('checkout.invoice', compact('pdfData', 'fileName'));
    }
}
