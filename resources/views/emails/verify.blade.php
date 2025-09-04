<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification de mail</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .verification-message h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        .verification-message p {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }

        .btn-blue {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }

        .btn-blue:hover {
            background-color: #0056b3;
        }

        .copy-link {
            color: #007bff;
            cursor: pointer;
            text-decoration: underline;
            font-size: 14px;
        }

        .copy-link:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="verification-message">
            <h1>Vérifiez votre email</h1>
            <p>Merci de vous être inscrit. Veuillez cliquer sur le bouton ci-dessous pour vérifier votre adresse email et activer votre compte.</p>
            <a style="color: white" href="{{ route('verify.email', $user->email_verified) }}" class="btn-blue">Vérifier votre adresse mail</a>
            <p>Si vous ne parvenez pas à cliquer sur le bouton, copiez et collez le lien suivant dans votre navigateur :</p>
            <p id="verification-link">{{ route('verify.email', $user->email_verified) }}</p>
            <p>Merci,<br>L'équipe de {{ FrontHelper::getAppName() }}</p>
        </div>
    </div>


</body>
</html>
