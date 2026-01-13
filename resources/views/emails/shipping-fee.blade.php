<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Frais de livraison</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #b78d65; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { padding: 30px; background: #f9f9f9; border-radius: 0 0 8px 8px; }
        .amount-box { background: white; border: 2px solid #b78d65; border-radius: 8px; padding: 20px; text-align: center; margin: 20px 0; }
        .amount { font-size: 2rem; font-weight: bold; color: #b78d65; }
        .cta-button { display: inline-block; background: #b78d65; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #777; font-size: 12px; }
        .details-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .details-table td { padding: 10px; border-bottom: 1px solid #eee; }
        .details-table td:first-child { font-weight: bold; width: 40%; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Frais de livraison</h1>
            <p>Commande #{{ $commande->code }}</p>
        </div>

        <div class="content">
            <p>Bonjour {{ $user->name }},</p>

            <p>Votre commande <strong>#{{ $commande->code }}</strong> est prête pour l'expédition.</p>

            @if($commande->delivery_method == 'tinda_awa')
            <div style="background: #fff9e6; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 4px;">
                <strong><i class="bi bi-info-circle"></i> Information importante :</strong><br>
                Votre commande sera livrée via notre partenaire <strong>Tinda Awa</strong>.
                Les frais de livraison ont été calculés en fonction de votre adresse et du moyen de transport choisi ({{ $commande->type ? $commande->type->label : 'N/A' }}).
            </div>
            @elseif($commande->delivery_method == 'cargo')
            <div style="background: #e8f4fd; border-left: 4px solid #0d6efd; padding: 15px; margin: 20px 0; border-radius: 4px;">
                <strong><i class="bi bi-info-circle"></i> Information importante :</strong><br>
                Votre commande est prête à être expédiée à votre <strong>cargo</strong>.
                Les frais de déplacement jusqu'à votre cargo sont indiqués ci-dessous.
            </div>
            @endif

            <div class="amount-box">
                <p style="margin: 0 0 10px 0;">Frais de livraison à régler :</p>
                <div class="amount">{{ number_format($commande->shipping_fee, 0, '.', ' ') }} XOF</div>

                @if($commande->estimated_delivery)
                <p style="margin: 10px 0 0 0;">
                    <small>Livraison estimée : {{ \Carbon\Carbon::parse($commande->estimated_delivery)->format('d/m/Y') }}</small>
                </p>
                @endif
            </div>

            <table class="details-table">
                <tr>
                    <td>Frais de livraison :</td>
                    <td>{{ number_format($commande->shipping_fee, 0, '.', ' ') }} XOF</td>
                </tr>
                <tr style="background: #f8f9fa; font-weight: bold;">
                    <td>Total à payer :</td>
                    <td>{{ number_format($commande->shipping_fee, 0, '.', ' ') }} XOF</td>
                </tr>
            </table>

            <div style="text-align: center;">
                <a href="{{ $paymentLink }}" class="cta-button">
                    Payer les frais de livraison
                </a>
                <p style="color: #666; font-size: 0.9rem;">
                    Ce lien est valable 7 jours. Passé ce délai, vous devrez contacter le support.
                </p>
            </div>

            @if($commande->observations)
            <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 20px;">
                <strong>Observations :</strong><br>
                {{ $commande->observations }}
            </div>
            @endif

            <p style="margin-top: 30px;">
                <strong>Rappel des informations de livraison :</strong><br>
                {{ $commande->address }}
            </p>

            <p style="margin-top: 20px;">
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
