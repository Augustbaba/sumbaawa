<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AdminWalletController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    //  GET /admin/wallet/recharge
    // ─────────────────────────────────────────────────────────────────────────

    public function index()
    {
        return view('back.pages.wallet.admin-recharge');
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  GET /admin/wallet/search-user?email=xxx
    //  Recherche AJAX : retourne le user si son email existe
    // ─────────────────────────────────────────────────────────────────────────

    public function searchUser(Request $request)
    {
        $email = trim($request->get('email', ''));

        if (strlen($email) < 3) {
            return response()->json(['found' => false]);
        }

        $user = User::where('email', $email)
            ->select('id', 'name', 'email', 'solde', 'avatar')
            ->first();

        if (!$user) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found'  => true,
            'user'   => [
                'id'     => $user->id,
                'name'   => $user->name ?? 'Sans nom',
                'email'  => $user->email,
                'solde'  => number_format($user->solde, 0, ',', ' ') . ' XOF',
                'initials' => strtoupper(substr($user->name ?? $user->email, 0, 1)),
            ],
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  POST /admin/wallet/recharge
    //  Créer la transaction et créditer le solde
    // ─────────────────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $request->validate([
            'email'  => ['required', 'email', 'exists:users,email'],
            'amount' => ['required', 'numeric', 'min:1'],
        ], [
            'email.exists'   => 'Aucun utilisateur trouvé avec cet email.',
            'amount.min'     => 'Le montant minimum est 1 XOF.',
            'amount.numeric' => 'Le montant doit être un nombre.',
        ]);

        $user      = User::where('email', $request->email)->firstOrFail();
        $amountXOF = (float) $request->amount;
        $adminId   = Auth::id();

        try {
            // ── 1. Enregistrer la transaction ─────────────────────────────
            Transaction::create([
                'reference'      => 'ADM-' . strtoupper(Str::random(10)),
                'transaction_id' => null,           // pas de transaction bancaire
                'amount'         => $amountXOF,
                'payment_method' => 'admin',        // méthode = rechargement admin
                'user_id'        => $user->id,
                'admin_id'       => $adminId,       // admin qui a effectué l'opération
            ]);

            // ── 2. Créditer le solde (atomique) ──────────────────────────
            $user->increment('solde', $amountXOF);

            Log::info('Admin wallet recharge', [
                'admin_id'   => $adminId,
                'user_id'    => $user->id,
                'user_email' => $user->email,
                'amount_xof' => $amountXOF,
                'new_solde'  => $user->fresh()->solde,
            ]);

            return redirect()
                ->route('admin.wallet.recharge')
                ->with('success',
                    "Solde de {$user->name} ({$user->email}) rechargé de "
                    . number_format($amountXOF, 0, ',', ' ')
                    . ' XOF avec succès. Nouveau solde : '
                    . number_format($user->fresh()->solde, 0, ',', ' ')
                    . ' XOF.');

        } catch (\Exception $e) {
            Log::error('Admin wallet recharge error: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erreur lors de la recharge : ' . $e->getMessage());
        }
    }
}
