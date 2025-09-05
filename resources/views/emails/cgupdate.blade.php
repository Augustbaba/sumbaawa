<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mise à jour des CGU</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: #007bff;
            color: #ffffff;
            text-align: center;
            padding: 15px;
            font-size: 20px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .content {
            padding: 20px;
            text-align: left;
            color: #333;
            line-height: 1.6;
        }
        .btn {
            display: inline-block;
            background: #007bff;
            color: #ffffff;
            padding: 12px 20px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            padding-top: 20px;
        }
        .highlight {
            color: #007bff;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Mise à jour des CGU</div>
        <div class="content">
            <p>Bonjour <strong>{{ $user->name ?? $user->email }}</strong>,</p>
            <p>Nous souhaitons vous informer que nos <span class="highlight">Conditions Générales d'Utilisation (CGU)</span> ont récemment été mises à jour.</p>
            <p>Cette mise à jour a été effectuée afin de mieux protéger vos droits et d'améliorer l'expérience utilisateur sur notre plateforme.</p>
            <p>Nous vous invitons à lire attentivement la nouvelle version des CGU en pièce jointe.</p>
            <p>Si vous avez des questions ou souhaitez obtenir des précisions, n'hésitez pas à Contactez-nous.</p>
            <p style="text-align: center;">
                <a href="{{ route('index') }}" class="btn" style="color: white;">Accéder à la plateforme</a>
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }} - Tous droits réservés.
        </div>
    </div>
</body>
</html>
