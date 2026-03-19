<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informations de Connexion</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 50px auto; background-color: #fff; padding: 0; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); overflow: hidden;">

        <!-- Header -->
        <div style="text-align: center; background-color: #4CAF50; padding: 20px;">
            <h1 style="margin: 0; color: #fff; font-size: 24px;">Bienvenue à {{ FrontHelper::getAppName() }}</h1>
        </div>

        <!-- Content -->
        <div style="padding: 20px;">
            <p style="font-size: 16px; line-height: 1.5; margin: 0 0 15px 0;">Bonjour {{ $user->name }},</p>

            <p style="font-size: 16px; line-height: 1.5; margin: 0 0 15px 0;">
                Nous sommes ravis de vous compter parmi nous. Voici vos identifiants de connexion :
            </p>

            <!-- Credentials Box -->
            <div style="margin-top: 20px; padding: 15px; background-color: #f9f9f9; border: 1px solid #ddd; border-radius: 8px;">
                <p style="margin: 0 0 10px 0; font-weight: bold;">
                    Email : <span style="font-weight: normal;">{{ $user->email }}</span>
                </p>
                <p style="margin: 0; font-weight: bold;">
                    Mot de passe : <span style="font-weight: normal;">{{ $password }}</span>
                </p>
            </div>

            <p style="font-size: 16px; line-height: 1.5; margin: 20px 0 15px 0;">
                Veuillez garder ces informations en lieu sûr.
            </p>

            <p style="font-size: 16px; line-height: 1.5; margin: 0 0 5px 0;">Merci,</p>
            <p style="font-size: 16px; line-height: 1.5; margin: 0;">L'équipe {{ FrontHelper::getAppName() }}</p>
        </div>

        <!-- Footer -->
        <div style="text-align: center; padding: 20px; font-size: 14px; color: #777; border-top: 1px solid #eee;">
            <p style="margin: 0;">&copy; 2024 {{ FrontHelper::getAppName() }}. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
