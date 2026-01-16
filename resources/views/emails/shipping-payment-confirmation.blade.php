<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmation paiement frais de livraison</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #28a745; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { padding: 30px; background: #f9f9f9; border-radius: 0 0 8px 8px; }
        .success-icon { font-size: 3rem; color: #28a745; text-align: center; margin-bottom: 20px; }
        .details-box { background: white; border: 2px solid #28a745; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .details-table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        .details-table td { padding: 8px; border-bottom: 1px solid #eee; }
        .details-table td:first-child { font-weight: bold; width: 40%; }
        .next-steps { background: #e8f4fd; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #777; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Paiement confirmé</h1>
            <p>Frais de livraison - Commande #{{ $commande->code }}</p>
        </div>

        <div class="content">
            <div class="success-icon">✓</div>

            <p>Bonjour {{ $user->name }},</p>

            <p>Nous vous confirmons le paiement des frais de livraison pour votre commande <strong>#{{ $commande->code }}</strong>.</p>

            <div class="details-box">
                <h3 style="margin-top: 0; color: #28a745;">Détails du paiement</h3>

                <table class="details-table">
                    <tr>
                        <td>Montant payé :</td>
                        <td>{{ FrontHelper::format_currency($commande->shipping_fee) }} </td>
                    </tr>
                    <tr>
                        <td>Type de livraison :</td>
                        <td>
                            @if($commande->delivery_method == 'tinda_awa')
                                Tinda Awa
                            @else
                                Cargo
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Date du paiement :</td>
                        <td>{{ now()->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>

            <div class="next-steps">
                <h4 style="margin-top: 0; color: #0d6efd;">Prochaines étapes :</h4>

                @if($commande->delivery_method == 'tinda_awa')
                    <p><strong>Livraison en cours :</strong><br>
                    Votre commande va maintenant être expédiée via Tinda Awa.</p>

                    @if($commande->estimated_delivery)
                    <p><strong>📅 Date de livraison estimée :</strong><br>
                    {{ \Carbon\Carbon::parse($commande->estimated_delivery)->format('d/m/Y') }}</p>
                    @endif

                    <p>Vous recevrez une notification lorsque votre commande sera expédiée.</p>

                @else
                    <p><strong>Prêt pour retrait :</strong><br>
                    Votre commande est maintenant disponible pour être récupérée par votre cargo.</p>

                    <p><strong>📍 Adresse de retrait :</strong><br>
                    {{ $commande->address }}</p>
                @endif
            </div>

            <p>Vous pouvez suivre l'état de votre commande depuis votre espace client sur notre site.</p>

            <p style="margin-top: 30px;">
                Cordialement,<br>
                <strong>L'équipe {{ config('app.name') }}</strong>
            </p>
        </div>

        <div class="footer">
            <p>
                Pour toute question, contactez-nous à support@sumbaawa.com<br>
                <small>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</small>
            </p>
        </div>
    </div>
</body>
</html>
