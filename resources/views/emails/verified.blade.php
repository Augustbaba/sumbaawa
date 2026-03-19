<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation d'inscription</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 20px auto; background: #ffffff; padding: 0; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">

        <!-- Header -->
        <div style="background: #007bff; color: #ffffff; text-align: center; padding: 15px; font-size: 20px; border-top-left-radius: 10px; border-top-right-radius: 10px;">
            Confirmation d'inscription
        </div>

        <!-- Content -->
        <div style="padding: 20px; text-align: left; color: #333; line-height: 1.6;">
            <p style="margin: 0 0 15px 0;">
                Bonjour <strong style="font-weight: bold;">{{ $user->name ?? $user->email }}</strong>,
            </p>

            <p style="margin: 0 0 15px 0;">
                Votre inscription a été validée avec succès !
            </p>

            <p style="margin: 0 0 15px 0;">
                Si vous avez la moindre question ou préoccupation, n'hésitez pas à nous contacter. Nous serons ravis de vous assister.
            </p>

            <p style="margin: 0; text-align: center;">
                <a href="{{ route('index') }}"
                   style="display: inline-block; background: #007bff; color: #ffffff; padding: 12px 20px; text-decoration: none; font-weight: bold; border-radius: 5px; margin-top: 20px;">
                    Accéder à la plateforme
                </a>
            </p>
        </div>

        <!-- Footer -->
        <div style="text-align: center; font-size: 12px; color: #777; padding: 20px; border-top: 1px solid #eee;">
            &copy; {{ date('Y') }} {{ config('app.name') }} - Tous droits réservés.
        </div>
    </div>
</body>
</html>
