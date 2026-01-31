<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\FrontHelper;
use App\Http\Controllers\Controller;
use App\Mail\VerifiedMail;
use App\Mail\VerifyMail;
use App\Models\Role;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => FrontHelper::getEnvFolder() .'images/avatars/user-avatar-placeholder.png',
            'email_verified' => md5(uniqid(rand(), true)),
        ]);
        $role = Role::where('name', 'customer')->first();
        $user->roles()->attach($role->id);

        event(new Registered($user));

        Mail::to($user->email)->send(new VerifyMail($user));

        return redirect()->route('login')->with('success', 'Inscription réussie!!! Veuillez activer votre compte en consultant votre boîte mail.');
    }

    public function verify(String $reference)
    {
        $user = User::where('email_verified', $reference)->first();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Lien de vérification invalide ou expiré.');
        }

        DB::update('update users set email_verified = ? where email_verified = ?', [null, $reference]);

        Mail::to($user->email)->send(new VerifiedMail($user));

        return redirect()->route('login')->with('success', 'Votre adresse e-mail a été vérifiée avec succès. Vous pouvez maintenant vous connecter.');
    }
}
