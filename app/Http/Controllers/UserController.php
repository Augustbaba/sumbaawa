<?php

namespace App\Http\Controllers;

use App\Helpers\FrontHelper;
use App\Mail\UserAccountCreated;
use App\Models\Pays;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['pays', 'roles'])->orderBy('name')->paginate(10);
        $roles = Role::all();
        return view('back.pages.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $pays = Pays::all();
        $roles = Role::all();
        return view('back.pages.users.create', compact('pays', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'phone' => 'nullable|string',
            'pays_id' => 'nullable|exists:pays,id',
            'address' => 'nullable|string|max:500',
            'role' => 'required|exists:roles,name',
        ]);

        // Génération du mot de passe aléatoire
        $password = Str::random(10);

        // Création de l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'pays_id' => $request->pays_id,
            'address' => $request->address,
            'password' => Hash::make($password),
            'status' => true,
            'avatar' => FrontHelper::getEnvFolder() .'images/avatars/user-avatar-placeholder.png',
        ]);

        // Assignation du rôle
        $user->assignRole($request->role);

        // Envoi du mail avec le mot de passe
        Mail::to($user->email)->send(new UserAccountCreated($user, $password));

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur créé avec succès. Un email a été envoyé avec ses identifiants.');
    }

    public function show(User $user)
    {
        $commandes = $user->commandes()
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
        return view('back.pages.users.show', compact('user', 'commandes'));
    }

    public function edit(User $user)
    {
        $pays = Pays::all();
        $roles = Role::all();
        return view('back.pages.users.edit', compact('user', 'pays', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string',
            'pays_id' => 'nullable|exists:pays,id',
            'address' => 'nullable|string|max:500',
            'role' => 'required|exists:roles,name',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'pays_id' => $request->pays_id,
            'address' => $request->address,
        ]);

        // Mise à jour du rôle
        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        // $user->delete();
        // return redirect()->route('users.index')
        //     ->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function toggleStatus(User $user)
    {
        $user->update(['status' => !$user->status]);

        $message = $user->status ?
            'Utilisateur activé avec succès.' :
            'Utilisateur désactivé avec succès.';

        return redirect()->route('users.index')
            ->with('success', $message);
    }
}
