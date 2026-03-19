<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Frais de livraison</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 20px auto; padding: 0; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">

        <!-- Header -->
        <div style="background: #b78d65; color: white; padding: 20px; text-align: center;">
            <h1 style="margin: 0 0 10px 0; font-size: 24px;">Frais de livraison</h1>
            <p style="margin: 0; font-size: 16px;">Commande #{{ $commande->code }}</p>
        </div>

        <!-- Content -->
        <div style="padding: 30px; background: #f9f9f9;">

            <p style="margin: 0 0 15px 0;">Bonjour {{ $user->name }},</p>

            <p style="margin: 0 0 15px 0;">
                Votre commande <strong style="font-weight: bold;">#{{ $commande->code }}</strong> est prête pour l'expédition.
            </p>

            @if($commande->delivery_method == 'tinda_awa')
            <!-- Info Tinda Awa -->
            <div style="background: #fff9e6; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 4px;">
                <strong style="font-weight: bold;">📌 Information importante :</strong><br>
                Votre commande sera livrée via notre partenaire <strong style="font-weight: bold;">Tinda Awa</strong>.
                Les frais de livraison ont été calculés en fonction de votre adresse et du moyen de transport choisi ({{ $commande->type ? $commande->type->label : 'N/A' }}).
            </div>
            @elseif($commande->delivery_method == 'cargo')
            <!-- Info Cargo -->
            <div style="background: #e8f4fd; border-left: 4px solid #0d6efd; padding: 15px; margin: 20px 0; border-radius: 4px;">
                <strong style="font-weight: bold;">📌 Information importante :</strong><br>
                Votre commande est prête à être expédiée à votre <strong style="font-weight: bold;">cargo</strong>.
                Les frais de déplacement jusqu'à votre cargo sont indiqués ci-dessous.
            </div>
            @endif

            <!-- Amount Box -->
            <div style="background: white; border: 2px solid #b78d65; border-radius: 8px; padding: 20px; text-align: center; margin: 20px 0;">
                <p style="margin: 0 0 10px 0;">Frais de livraison à régler :</p>
                <div style="font-size: 2rem; font-weight: bold; color: #b78d65;">{{ FrontHelper::format_currency($commande->shipping_fee) }}</div>

                @if($commande->estimated_delivery)
                <p style="margin: 10px 0 0 0;">
                    <small style="font-size: 12px;">Livraison estimée : {{ \Carbon\Carbon::parse($commande->estimated_delivery)->format('d/m/Y') }}</small>
                </p>
                @endif
            </div>

            <!-- Details Table -->
            <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; width: 40%;">Frais de livraison :</td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ FrontHelper::format_currency($commande->shipping_fee) }}</td>
                </tr>
                <tr style="background: #f8f9fa; font-weight: bold;">
                    <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;">Total à payer :</td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ FrontHelper::format_currency($commande->shipping_fee) }}</td>
                </tr>
            </table>

            <!-- CTA Button -->
            <div style="text-align: center;">
                <a href="{{ $paymentLink }}"
                   style="display: inline-block; background: #b78d65; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 20px 0;">
                    Payer les frais de livraison
                </a>
                <p style="color: #666; font-size: 0.9rem; margin: 0;">
                    Ce lien est valable 7 jours. Passé ce délai, vous devrez contacter le support.
                </p>
            </div>

            @if($commande->observations)
            <!-- Observations -->
            <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 20px;">
                <strong style="font-weight: bold;">Observations :</strong><br>
                {{ $commande->observations }}
            </div>
            @endif

            <!-- Delivery Info -->
            <p style="margin: 30px 0 0 0;">
                <strong style="font-weight: bold;">Rappel des informations de livraison :</strong><br>
                {{ $commande->address }}
            </p>

            <!-- Signature -->
            <p style="margin: 20px 0 0 0;">
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
