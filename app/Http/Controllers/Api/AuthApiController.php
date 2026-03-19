<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Mail\VerifyMail;
use App\Models\Role;
use Illuminate\Support\Facades\Mail;

class AuthApiController extends Controller
{
    /**
     * Inscription
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name'     => ['required', 'string'],
            'email'    => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => 'images/avatars/user-avatar-placeholder.png',
            'email_verified' => md5(uniqid(rand(), true)),
        ]);

        $role = Role::where('name', 'customer')->first();
        $user->roles()->attach($role->id);


        Mail::to($user->email)->send(new VerifyMail($user));

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Inscription réussie.',
            'token'   => $token,
            'user'    => $this->formatUser($user),
        ], 201);
    }

    /**
     * Connexion
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun compte trouvé avec cet email.',
            ], 422);
        }

        if ($user->status == false) {
            return response()->json([
                'success' => false,
                'message' => 'Compte désactivé. Veuillez contacter le support.',
            ], 403);
        }

        if (! Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Mot de passe incorrect.',
            ], 422);
        }

        // Révoquer les anciens tokens mobile si souhaité (optionnel)
        $user->tokens()->where('name', 'mobile-app')->delete();

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie.',
            'token'   => $token,
            'user'    => $this->formatUser($user),
        ]);
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie.',
        ]);
    }

    /**
     * Profil utilisateur connecté
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'user'    => $this->formatUser($request->user()->load('currency')),
        ]);
    }

    /**
     * Formater les données utilisateur renvoyées au mobile
     */
    private function formatUser(User $user): array
    {
        return [
            'id'                    => $user->id,
            'name'                  => $user->name,
            'email'                 => $user->email,
            'avatar'                => $user->avatar ? asset($user->avatar) : null,
            'phone'                 => $user->phone ?? null,
            'address'               => $user->address ?? null,
            'preferred_currency_id' => $user->preferred_currency_id ?? null,
            'preferred_currency'    => $user->relationLoaded('currency') && $user->currency
                                        ? [
                                            'id'     => $user->currency->id,
                                            'code'   => $user->currency->code,
                                            'symbol' => $user->currency->symbol,
                                          ]
                                        : null,
        ];
    }
}
