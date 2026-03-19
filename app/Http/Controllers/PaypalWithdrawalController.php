<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\PaypalWithdrawal;
use App\Models\Travel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaypalWithdrawalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Entrées PayPal
        $commandesTotal = Commande::where('payment_method', 'paypal')
            ->where('payment_status', 'paid')
            ->sum('total_amount'); // en XOF

        $shippingTotal = Commande::where('shipping_payment_id', '!=', null)
            ->where('shipping_status', 'fee_paid')
            // On exclut les paiements ElongoPay en vérifiant que le payment_id
            // correspond à un format PayPal (commence par un order ID PayPal)
            // Si vous stockez la méthode séparément, filtrez ici
            ->sum('shipping_fee'); // en XOF

        $travelTotal = Travel::where('payment_method', 'paypal')
            ->where('payment_status', 'paid')
            ->sum('amount_xof'); // en XOF

        $totalIn = $commandesTotal + $shippingTotal + $travelTotal;

        // Retraits
        $withdrawals = PaypalWithdrawal::with('user')
            ->latest()
            ->paginate(15);

        $totalWithdrawals = PaypalWithdrawal::sum('amount');

        // Solde net (en XOF)
        $balance = $totalIn - $totalWithdrawals;

        return view('back.pages.paypal-balance.index', compact(
            'commandesTotal',
            'shippingTotal',
            'travelTotal',
            'totalIn',
            'withdrawals',
            'totalWithdrawals',
            'balance'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'note'   => 'nullable|string|max:255',
        ]);

        PaypalWithdrawal::create([
            'user_id' => Auth::id(),
            'amount'  => $request->amount,
            'note'    => $request->note,
        ]);

        return redirect()->route('admin.paypal-balance.index')
            ->with('success', 'Retrait enregistré avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PaypalWithdrawal $paypalWithdrawal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaypalWithdrawal $paypalWithdrawal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaypalWithdrawal $paypalWithdrawal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        PaypalWithdrawal::findOrFail($id)->delete();

        return redirect()->route('admin.paypal-balance.index')
            ->with('success', 'Retrait supprimé.');
    }
}
