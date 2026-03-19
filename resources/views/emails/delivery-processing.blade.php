<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Processus de livraison</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 20px auto; padding: 0; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">

        <!-- Header -->
        <div style="background: #b78d65; color: white; padding: 20px; text-align: center;">
            <h1 style="margin: 0; font-size: 24px;">Processus de livraison</h1>
        </div>

        <!-- Content -->
        <div style="padding: 30px; background: #f9f9f9;">

            <p style="margin: 0 0 15px 0;">Bonjour {{ $user->name }},</p>

            <p style="margin: 0 0 15px 0;">
                Votre commande <strong style="font-weight: bold;">#{{ $order->code }}</strong> a bien été enregistrée.
            </p>

            <!-- Notice -->
            <div style="background: #fff9e6; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0;">
                <strong style="font-weight: bold;">Important :</strong>
                @if(isset($deliveryData['deliveryMethod']) && $deliveryData['deliveryMethod'] === 'tinda_awa')
                Votre commande sera livrée via notre partenaire Tinda Awa. Les frais de livraison seront calculés en fonction de votre adresse et du moyen de transport choisi ({{ $order->type ? $order->type->label : 'N/A' }}) et vous recevrez un autre email pour effectuer ce paiement.
                @else
                Votre commande sera préparée pour être envoyée à votre cargo. Nous analyserons les frais de déplacement jusqu'à votre cargo et vous ferons un retour via un autre email pour effectuer ce paiement.
                @endif
            </div>

            <!-- Steps -->
            <div style="margin: 20px 0;">
                <h3 style="margin: 0 0 15px 0; color: #b78d65;">Étapes suivantes :</h3>

                <!-- Step 1 -->
                <div style="display: flex; align-items: flex-start; margin-bottom: 15px;">
                    <div style="background: #b78d65; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; flex-shrink: 0; font-weight: bold;">1</div>
                    <div style="flex: 1;">
                        <strong style="font-weight: bold;">Préparation de la commande</strong>
                        <p style="margin: 5px 0 0 0;">Nous préparons vos articles pour l'expédition.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div style="display: flex; align-items: flex-start; margin-bottom: 15px;">
                    <div style="background: #b78d65; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; flex-shrink: 0; font-weight: bold;">2</div>
                    <div style="flex: 1;">
                        <strong style="font-weight: bold;">Calcul des frais de livraison</strong>
                        <p style="margin: 5px 0 0 0;">Nous calculons les frais exacts de livraison.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div style="display: flex; align-items: flex-start; margin-bottom: 15px;">
                    <div style="background: #b78d65; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; flex-shrink: 0; font-weight: bold;">3</div>
                    <div style="flex: 1;">
                        <strong style="font-weight: bold;">Paiement des frais</strong>
                        <p style="margin: 5px 0 0 0;">Vous recevrez un email pour payer les frais de livraison.</p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div style="display: flex; align-items: flex-start; margin-bottom: 15px;">
                    <div style="background: #b78d65; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; flex-shrink: 0; font-weight: bold;">4</div>
                    <div style="flex: 1;">
                        <strong style="font-weight: bold;">Expédition</strong>
                        <p style="margin: 5px 0 0 0;">Une fois le paiement confirmé, nous expédions votre commande.</p>
                    </div>
                </div>
            </div>

            <p style="margin: 20px 0 0 0;">
                Merci pour votre confiance,<br>
                <strong style="font-weight: bold;">L'équipe {{ config('app.name') }}</strong>
            </p>
        </div>

        <!-- Footer -->
        <div style="text-align: center; padding: 20px; color: #777; font-size: 12px; background: #ffffff; border-top: 1px solid #eee;">
            <p style="margin: 0;">Pour toute question, contactez-nous à support@sumbaawa.com</p>
        </div>
    </div>
</body>
</html>
