<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pays;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class ProfileApiController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    //  GET /api/v1/profile
    // ─────────────────────────────────────────────────────────────────────────

    public function show()
    {
        $user = Auth::user()->load('pays', 'preferred_currency');

        return response()->json([
            'success' => true,
            'user'    => $this->formatUser($user),
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  GET /api/v1/pays  — liste des pays pour le dropdown
    // ─────────────────────────────────────────────────────────────────────────

    public function pays()
    {
        $pays = Pays::orderBy('name')->get(['id', 'name']);

        return response()->json([
            'success' => true,
            'pays'    => $pays,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  PUT /api/v1/profile  — modifier les infos personnelles
    //  Miroir exact de ProfileController::update() web
    // ─────────────────────────────────────────────────────────────────────────

    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name'    => ['nullable', 'string', 'max:255'],
            'email'   => ['required', 'string', 'email', 'max:255',
                          'unique:users,email,' . $user->id],
            'phone'   => ['nullable', 'string', 'max:20',
                          'regex:/^[0-9\-\+\s\(\)]+$/'],
            'pays_id' => ['nullable', 'exists:pays,id'],
            'address' => ['nullable', 'string', 'max:500'],
        ], [
            'email.unique'  => 'Cette adresse email est déjà utilisée.',
            'phone.regex'   => 'Le format du téléphone est invalide.',
            'email.required'=> 'L\'email est obligatoire.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user->name    = $request->name;
        $user->email   = $request->email;
        $user->phone   = $request->phone;
        $user->pays_id = $request->pays_id;
        $user->address = $request->address;
        $user->save();

        Log::info('Profil mis à jour via API mobile', ['user_id' => $user->id]);

        return response()->json([
            'success' => true,
            'message' => 'Profil mis à jour avec succès.',
            'user'    => $this->formatUser($user->fresh()->load('pays', 'currency')),
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  PUT /api/v1/profile/password  — changer le mot de passe
    //  Miroir exact de ProfileController::updatePassword() web
    // ─────────────────────────────────────────────────────────────────────────

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'current_password.required' => 'Le mot de passe actuel est requis.',
            'password.required'         => 'Le nouveau mot de passe est requis.',
            'password.confirmed'        => 'Les mots de passe ne correspondent pas.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Vérifier le mot de passe actuel
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Le mot de passe actuel est incorrect.',
            ], 422);
        }

        // Vérifier que le nouveau est différent de l'ancien
        if (Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Le nouveau mot de passe doit être différent de l\'actuel.',
            ], 422);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        Log::info('Mot de passe changé via API mobile', ['user_id' => $user->id]);

        return response()->json([
            'success' => true,
            'message' => 'Mot de passe changé avec succès.',
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  Helper — formater l'utilisateur pour la réponse JSON
    //  Structure identique à UserModel.fromJson() Flutter
    // ─────────────────────────────────────────────────────────────────────────

    private function formatUser($user): array
    {
        return [
            'id'                       => $user->id,
            'name'                     => $user->name,
            'email'                    => $user->email,
            'phone'                    => $user->phone,
            'address'                  => $user->address,
            'avatar'                   => $user->avatar
                                            ? asset('storage/' . $user->avatar)
                                            : null,
            'pays_id'                  => $user->pays_id,
            'pays'                     => $user->pays
                                            ? ['id' => $user->pays->id, 'name' => $user->pays->name]
                                            : null,
            'preferred_currency_id'    => $user->preferred_currency_id,
            'preferred_currency'       => $user->currency
                                            ? [
                                                'code'   => $user->currency->code,
                                                'symbol' => $user->currency->symbol,
                                              ]
                                            : null,
            'solde'                    => (float) $user->solde,
            'created_at'               => $user->created_at->toIso8601String(),
        ];
    }
}
