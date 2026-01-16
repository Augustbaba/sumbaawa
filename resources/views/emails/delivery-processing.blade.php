<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Processus de livraison</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #b78d65; color: white; padding: 20px; text-align: center; }
        .content { padding: 30px; background: #f9f9f9; }
        .notice { background: #fff9e6; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; }
        .steps { margin: 20px 0; }
        .step { display: flex; align-items: flex-start; margin-bottom: 15px; }
        .step-number { background: #b78d65; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; flex-shrink: 0; }
        .footer { text-align: center; padding: 20px; color: #777; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Processus de livraison</h1>
        </div>

        <div class="content">
            <p>Bonjour {{ $user->name }},</p>

            <p>Votre commande <strong>#{{ $order->code }}</strong> a bien été enregistrée.</p>

            <div class="notice">
                <strong>Important :</strong>
                @if(isset($deliveryData['deliveryMethod']) && $deliveryData['deliveryMethod'] === 'tinda_awa')
                Votre commande sera livrée via notre partenaire Tinda Awa. Les frais de livraison seront calculés en fonction de votre adresse et du moyen de transport choisi ({{ $order->type ? $order->type->label : 'N/A' }}) et vous recevrez un autre email pour effectuer ce paiement.
                @else
                Votre commande sera préparée pour être envoyée à votre cargo. Nous analyserons les frais de déplacement jusqu'à votre cargo et vous ferons un retour via un autre email pour effectuer ce paiement.
                @endif
            </div>

            <div class="steps">
                <h3>Étapes suivantes :</h3>

                <div class="step">
                    <div class="step-number">1</div>
                    <div>
                        <strong>Préparation de la commande</strong>
                        <p>Nous préparons vos articles pour l'expédition.</p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">2</div>
                    <div>
                        <strong>Calcul des frais de livraison</strong>
                        <p>Nous calculons les frais exacts de livraison.</p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">3</div>
                    <div>
                        <strong>Paiement des frais</strong>
                        <p>Vous recevrez un email pour payer les frais de livraison.</p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">4</div>
                    <div>
                        <strong>Expédition</strong>
                        <p>Une fois le paiement confirmé, nous expédions votre commande.</p>
                    </div>
                </div>
            </div>

            <p>Merci pour votre confiance,<br>L'équipe {{ config('app.name') }}</p>
        </div>

        <div class="footer">
            <p>Pour toute question, contactez-nous à support@sumbaawa.com</p>
        </div>
    </div>
</body>
</html>
