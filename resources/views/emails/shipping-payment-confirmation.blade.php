<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmation paiement frais de livraison</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 20px auto; padding: 0; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">

        <!-- Header -->
        <div style="background: #28a745; color: white; padding: 20px; text-align: center;">
            <h1 style="margin: 0 0 10px 0; font-size: 24px;">Paiement confirmé</h1>
            <p style="margin: 0; font-size: 16px;">Frais de livraison - Commande #{{ $commande->code }}</p>
        </div>

        <!-- Content -->
        <div style="padding: 30px; background: #f9f9f9;">

            <!-- Success Icon -->
            <div style="font-size: 3rem; color: #28a745; text-align: center; margin-bottom: 20px;">✓</div>

            <p style="margin: 0 0 15px 0;">Bonjour {{ $user->name }},</p>

            <p style="margin: 0 0 15px 0;">
                Nous vous confirmons le paiement des frais de livraison pour votre commande
                <strong style="font-weight: bold;">#{{ $commande->code }}</strong>.
            </p>

            <!-- Details Box -->
            <div style="background: white; border: 2px solid #28a745; border-radius: 8px; padding: 20px; margin: 20px 0;">
                <h3 style="margin: 0 0 15px 0; color: #28a745; font-size: 18px;">Détails du paiement</h3>

                <table style="width: 100%; border-collapse: collapse; margin: 10px 0;">
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold; width: 40%;">Montant payé :</td>
                        <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ FrontHelper::format_currency($commande->shipping_fee) }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Type de livraison :</td>
                        <td style="padding: 8px; border-bottom: 1px solid #eee;">
                            @if($commande->delivery_method == 'tinda_awa')
                                Tinda Awa
                            @else
                                Cargo
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Date du paiement :</td>
                        <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ now()->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>

            <!-- Next Steps -->
            <div style="background: #e8f4fd; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <h4 style="margin: 0 0 10px 0; color: #0d6efd; font-size: 16px;">Prochaines étapes :</h4>

                @if($commande->delivery_method == 'tinda_awa')
                    <p style="margin: 0 0 10px 0;">
                        <strong style="font-weight: bold;">Livraison en cours :</strong><br>
                        Votre commande va maintenant être expédiée via Tinda Awa.
                    </p>

                    @if($commande->estimated_delivery)
                    <p style="margin: 0 0 10px 0;">
                        <strong style="font-weight: bold;">📅 Date de livraison estimée :</strong><br>
                        {{ \Carbon\Carbon::parse($commande->estimated_delivery)->format('d/m/Y') }}
                    </p>
                    @endif

                    <p style="margin: 0;">Vous recevrez une notification lorsque votre commande sera expédiée.</p>

                @else
                    <p style="margin: 0 0 10px 0;">
                        <strong style="font-weight: bold;">Prêt pour retrait :</strong><br>
                        Votre commande est maintenant disponible pour être récupérée par votre cargo.
                    </p>

                    <p style="margin: 0;">
                        <strong style="font-weight: bold;">📍 Adresse de retrait :</strong><br>
                        {{ $commande->address }}
                    </p>
                @endif
            </div>

            <p style="margin: 0 0 15px 0;">Vous pouvez suivre l'état de votre commande depuis votre espace client sur notre site.</p>

            <p style="margin: 30px 0 0 0;">
                Cordialement,<br>
                <strong style="font-weight: bold;">L'équipe {{ config('app.name') }}</strong>
            </p>
        </div>

        <!-- Footer -->
        <div style="text-align: center; padding: 20px; color: #777; font-size: 12px; background: #ffffff; border-top: 1px solid #eee;">
            <p style="margin: 0;">
                Pour toute question, contactez-nous à support@sumbaawa.com<br>
                <small style="font-size: 11px;">Cet email a été envoyé automatiquement, merci de ne pas y répondre.</small>
            </p>
        </div>
    </div>
</body>
</html>
