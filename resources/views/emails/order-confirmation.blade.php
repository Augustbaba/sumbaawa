<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmation de commande</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #b78d65; color: white; padding: 20px; text-align: center; }
        .content { padding: 30px; background: #f9f9f9; }
        .order-details { background: white; padding: 20px; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #777; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Confirmation de commande</h1>
        </div>

        <div class="content">
            <p>Bonjour {{ $user->name }},</p>

            <p>Nous vous confirmons la réception de votre commande <strong>#{{ $order->code }}</strong>.</p>

            <div class="order-details">
                <h3>Détails de la commande</h3>
                <p><strong>Date :</strong> {{ $order->created_at }}</p>
                <p><strong>Total :</strong> {{ number_format($order->total_amount, 0, '.', ' ') }} XOF</p>
                <p><strong>Statut :</strong> {{ $order->status_label }}</p>

                @if($order->address)
                <p><strong>Adresse de livraison :</strong><br>
                {{ $order->address }}</p>
                @endif
            </div>

            <p>Vous pouvez suivre l'état de votre commande depuis votre espace client.</p>

            <p>Cordialement,<br>L'équipe {{ config('app.name') }}</p>
        </div>

        <div class="footer">
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>
