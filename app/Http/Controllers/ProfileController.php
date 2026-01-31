<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Pays;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Afficher le profil de l'utilisateur connecté
     */
    public function show()
    {
        $user = Auth::user();
        $pays = Pays::orderBy('name')->get();

        return view('profile.show', compact('user', 'pays'));
    }

    /**
     * Afficher le formulaire d'édition du profil
     */
    public function edit()
    {
        $user = Auth::user();
        $pays = Pays::orderBy('name')->get();

        return view('profile.edit', compact('user', 'pays'));
    }

    /**
     * Mettre à jour les informations personnelles
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\-\+\s\(\)]+$/'],
            'pays_id' => ['nullable', 'exists:pays,id'],
            'address' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ], [
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'phone.regex' => 'Le format du téléphone est invalide.',
            'avatar.image' => 'Le fichier doit être une image.',
            'avatar.max' => 'L\'image ne doit pas dépasser 2 Mo.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'personal');
        }

        // Mettre à jour les informations de base
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->pays_id = $request->pays_id;
        $user->address = $request->address;

        // Gérer l'upload de l'avatar
        if ($request->hasFile('avatar')) {
            // Supprimer l'ancien avatar s'il existe
            if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
                Storage::delete('public/' . $user->avatar);
            }

            // Générer un nom unique pour l'avatar
            $avatarName = 'avatars/' . Str::uuid() . '.' . $request->avatar->extension();

            // Stocker l'avatar
            $request->avatar->storeAs('public', $avatarName);

            // Enregistrer le chemin de l'avatar
            $user->avatar = $avatarName;
        }

        $user->save();



        return redirect()->route('profile.show')
            ->with('success', 'Votre profil a été mis à jour avec succès.')
            ->with('active_tab', 'personal');
    }

    /**
     * Afficher le formulaire de changement de mot de passe
     */
    public function editPassword()
    {
        return view('profile.edit-password');
    }

    /**
     * Changer le mot de passe
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'current_password.required' => 'Le mot de passe actuel est requis.',
            'password.required' => 'Le nouveau mot de passe est requis.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        // Vérifier le mot de passe actuel
        if (!Hash::check($request->current_password, $user->password)) {
            $validator->errors()->add('current_password', 'Le mot de passe actuel est incorrect.');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'password');
        }

        // Vérifier que le nouveau mot de passe est différent de l'ancien
        if (Hash::check($request->password, $user->password)) {
            $validator->errors()->add('password', 'Le nouveau mot de passe doit être différent de l\'actuel.');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'password');
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'password');
        }

        // Mettre à jour le mot de passe
        $user->password = Hash::make($request->password);
        $user->save();



        // Déconnecter les autres sessions
        Auth::logoutOtherDevices($request->password);

        return redirect()->route('profile.show')
            ->with('success', 'Votre mot de passe a été changé avec succès.')
            ->with('active_tab', 'password');
    }

    /**
     * Supprimer l'avatar
     */
    public function deleteAvatar(Request $request)
    {
        $user = Auth::user();

        if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
            Storage::delete('public/' . $user->avatar);
            $user->avatar = null;
            $user->save();



            return response()->json([
                'success' => true,
                'message' => 'Avatar supprimé avec succès.',
                'avatar_url' => asset('images/default-avatar.png')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Aucun avatar à supprimer.'
        ], 400);
    }

    
}
