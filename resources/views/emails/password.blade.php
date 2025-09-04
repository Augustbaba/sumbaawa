<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            background-color: #4CAF50;
            padding: 20px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .header h1 {
            margin: 0;
            color: #fff;
        }
        .content {
            padding: 20px;
        }
        .content p {
            font-size: 16px;
            line-height: 1.5;
        }
        .content .credentials {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .content .credentials p {
            margin: 0;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
    <title>Informations de Connexion</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bienvenue à {{ FrontHelper::getAppName() }}</h1>
        </div>
        <div class="content">
            <p>Bonjour {{ $user->name }},</p>
            <p>Nous sommes ravis de vous compter parmi nous. Voici vos identifiants de connexion :</p>
            <div class="credentials">
                <p>Email : <span>{{ $user->email }}</span></p>
                <p>Mot de passe : <span>{{ $password }}</span></p>
            </div>
            <p>Veuillez garder ces informations en lieu sûr.</p>
            <p>Merci,</p>
            <p>L'équipe {{ FrontHelper::getAppName() }}</p>
        </div>
        <div class="footer">
            <p>&copy; 2024 {{ FrontHelper::getAppName() }}. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
