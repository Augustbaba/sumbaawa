@component('mail::message')
# Bonjour {{ $user->name ?? 'Utilisateur' }},

Votre compte a été créé avec succès sur notre plateforme.

Voici vos informations de connexion :
- **Email :** {{ $user->email }}
- **Mot de passe :** {{ $password }}

@component('mail::button', ['url' => $loginUrl])
Se connecter
@endcomponent

Pour des raisons de sécurité, nous vous recommandons de changer votre mot de passe après votre première connexion.

Cordialement,
L'équipe {{ config('app.name') }}
@endcomponent
