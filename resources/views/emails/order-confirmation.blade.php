<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmation de commande</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 20px auto; padding: 0; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">

        <!-- Header -->
        <div style="background: #b78d65; color: white; padding: 20px; text-align: center;">
            <h1 style="margin: 0; font-size: 24px;">Confirmation de commande</h1>
        </div>

        <!-- Content -->
        <div style="padding: 30px; background: #f9f9f9;">

            <p style="margin: 0 0 15px 0;">Bonjour {{ $user->name }},</p>

            <p style="margin: 0 0 15px 0;">
                Nous vous confirmons la réception de votre commande
                <strong style="font-weight: bold;">#{{ $order->code }}</strong>.
            </p>

            <!-- Order Details -->
            <div style="background: white; padding: 20px; border-radius: 5px; margin: 20px 0;">
                <h3 style="margin: 0 0 15px 0; color: #b78d65;">Détails de la commande</h3>

                <p style="margin: 0 0 10px 0;">
                    <strong style="font-weight: bold;">Date :</strong>
                    {{ $order->created_at }}
                </p>

                <p style="margin: 0 0 10px 0;">
                    <strong style="font-weight: bold;">Total :</strong>
                    {{ number_format($order->total_amount, 0, '.', ' ') }} XOF
                </p>

                <p style="margin: 0 0 10px 0;">
                    <strong style="font-weight: bold;">Statut :</strong>
                    {{ $order->status_label }}
                </p>

                @if($order->address)
                <p style="margin: 0;">
                    <strong style="font-weight: bold;">Adresse de livraison :</strong><br>
                    {{ $order->address }}
                </p>
                @endif
            </div>

            <p style="margin: 0 0 15px 0;">
                Vous pouvez suivre l'état de votre commande depuis votre espace client.
            </p>

            <p style="margin: 0;">
                Cordialement,<br>
                <strong style="font-weight: bold;">L'équipe {{ config('app.name') }}</strong>
            </p>
        </div>

        <!-- Footer -->
        <div style="text-align: center; padding: 20px; color: #777; font-size: 12px; background: #ffffff; border-top: 1px solid #eee;">
            <p style="margin: 0;">
                Cet email a été envoyé automatiquement, merci de ne pas y répondre.
            </p>
        </div>
    </div>
</body>
</html>
