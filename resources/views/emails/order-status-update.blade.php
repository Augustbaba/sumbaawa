<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mise à jour statut commande</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 20px auto; padding: 0; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">

        <!-- Header -->
        <div style="background: #b78d65; color: white; padding: 20px; text-align: center;">
            <h1 style="margin: 0 0 10px 0; font-size: 24px;">Mise à jour de votre commande</h1>
            <p style="margin: 0; font-size: 16px;">Commande #{{ $commande->code }}</p>
        </div>

        <!-- Content -->
        <div style="padding: 30px; background: #f9f9f9;">

            <p style="margin: 0 0 15px 0;">Bonjour {{ $user->name }},</p>

            <p style="margin: 0 0 15px 0;">
                Le statut de votre commande <strong style="font-weight: bold;">#{{ $commande->code }}</strong> a été mis à jour.
            </p>

            <!-- Status Box -->
            <div style="background: white; border-radius: 8px; padding: 20px; margin: 20px 0; text-align: center;">

                @php
                    $icons = [
                        'delivered' => '🎁',
                        'in_transit' => '🚚',
                        'arrived' => '📍',
                        'ready_pickup' => '📦',
                        'picked_up' => '✅',
                        'processing' => '⚙️',
                        'cancelled' => '❌',
                        'pending' => '⏳'
                    ];
                    $icon = $icons[$commande->status] ?? '📋';
                @endphp

                <div style="font-size: 3rem; margin-bottom: 10px;">{{ $icon }}</div>

                <div style="margin-bottom: 10px;">
                    <span style="color: #666; text-decoration: line-through;">{{ $oldStatusLabel }}</span>
                    <span style="color: #b78d65; font-size: 1.5rem; margin: 0 10px;">→</span>
                    <span style="color: #b78d65; font-weight: bold; font-size: 1.2rem;">{{ $newStatusLabel }}</span>
                </div>

                <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #eee;">
                    <small style="font-size: 12px;">
                        <strong style="font-weight: bold;">Date :</strong> {{ now()->format('d/m/Y H:i') }}
                    </small>
                </div>
            </div>

            <!-- Message personnalisé selon le statut -->
            @if($commande->status == 'ready_pickup')
                @if($commande->delivery_method == 'cargo')
                <div style="background: #e8f4fd; padding: 15px; border-radius: 5px; margin: 20px 0;">
                    <strong style="font-weight: bold;">Votre commande est prête pour retrait :</strong><br>
                    Votre commande est disponible chez nous. Vous pouvez la faire récupérer par votre cargo.
                    @if($commande->shipping_fee)
                        <br><small style="font-size: 12px;">Les frais de livraison sont de {{ number_format($commande->shipping_fee, 0, '.', ' ') }} XOF.</small>
                    @endif
                </div>
                @elseif($commande->delivery_method == 'tinda_awa')
                <div style="background: #fff9e6; padding: 15px; border-radius: 5px; margin: 20px 0;">
                    <strong style="font-weight: bold;">Préparation de la livraison :</strong><br>
                    Votre commande est prête pour l'expédition via Tinda Awa. Les frais de livraison vous seront communiqués sous peu.
                </div>
                @endif

            @elseif($commande->status == 'in_transit')
                <div style="background: #d1ecf1; padding: 15px; border-radius: 5px; margin: 20px 0;">
                    <strong style="font-weight: bold;">✈️ Votre commande est en route :</strong><br>
                    Votre commande a été expédiée et est en cours de livraison via Tinda Awa.
                    @if($commande->estimated_delivery)
                        <br>Livraison estimée : {{ \Carbon\Carbon::parse($commande->estimated_delivery)->format('d/m/Y') }}
                    @endif
                </div>

            @elseif($commande->status == 'delivered')
                <div style="background: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0;">
                    <strong style="font-weight: bold;">Livraison effectuée :</strong><br>
                    Votre commande a été livrée avec succès. Nous espérons que vous serez satisfait de votre achat !
                </div>

            @elseif($commande->status == 'picked_up')
                <div style="background: #e2e3e5; padding: 15px; border-radius: 5px; margin: 20px 0;">
                    <strong style="font-weight: bold;">Commande récupérée :</strong><br>
                    Votre commande a été récupérée par votre cargo. Elle sera bientôt en route vers vous.
                </div>

            @elseif($commande->status == 'cancelled')
                <div style="background: #f8d7da; padding: 15px; border-radius: 5px; margin: 20px 0;">
                    <strong style="font-weight: bold;">Commande annulée :</strong><br>
                    Votre commande a été annulée. Si vous avez des questions, n'hésitez pas à nous contacter.
                </div>
            @endif

            <p style="margin: 20px 0 15px 0;">
                Vous pouvez suivre l'état de votre commande depuis votre espace client sur notre site.
            </p>

            <p style="margin: 0;">
                Cordialement,<br>
                <strong style="font-weight: bold;">L'équipe {{ config('app.name') }}</strong>
            </p>
        </div>

        <!-- Footer -->
        <div style="text-align: center; padding: 20px; color: #777; font-size: 12px; background: #ffffff; border-top: 1px solid #eee;">
            <p style="margin: 0;">
                {{ config('app.name') }}<br>
                <small style="font-size: 11px;">Pour toute question, contactez-nous à support@sumbaawa.com</small>
            </p>
        </div>
    </div>
</body>
</html>
