<!-- resources/views/emails/actuality_notification.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Actualité</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #fe7f4c;
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            color: #333333;
            font-size: 16px;
            line-height: 1.6;
        }
        a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #fe7f4c;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 20px;
        }
        a:hover {
            background-color: #e76e3a;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            color: #888888;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h1>{{ $title }}</h1>
        <p>{!! $excerpt !!}...</p>
        <a href="{{ $url }}" style="color: white;">Lire la suite</a>
        <div class="footer">
            <p>Vous recevez cet email car vous êtes abonné à notre newsletter.</p>
            <p>&copy; 2024 {{ FrontHelper::getAppName() }}. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
