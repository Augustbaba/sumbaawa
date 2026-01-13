<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mise à jour statut commande</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #b78d65; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { padding: 30px; background: #f9f9f9; border-radius: 0 0 8px 8px; }
        .status-box { background: white; border-radius: 8px; padding: 20px; margin: 20px 0; text-align: center; }
        .old-status { color: #666; text-decoration: line-through; }
        .new-status { color: #b78d65; font-weight: bold; font-size: 1.2rem; }
        .arrow { color: #b78d65; font-size: 1.5rem; margin: 0 10px; }
        .status-icon { font-size: 3rem; margin-bottom: 10px; }
        .delivered { color: #28a745; }
        .in-transit { color: #17a2b8; }
        .processing { color: #007bff; }
        .cancelled { color: #dc3545; }
        .footer { text-align: center; padding: 20px; color: #777; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Mise à jour de votre commande</h1>
            <p>Commande #{{ $commande->code }}</p>
        </div>

        <div class="content">
            <p>Bonjour {{ $user->name }},</p>

            <p>Le statut de votre commande <strong>#{{ $commande->code }}</strong> a été mis à jour.</p>

            <div class="status-box">
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

                <div class="status-icon">{{ $icon }}</div>

                <div style="margin-bottom: 10px;">
                    <span class="old-status">{{ $oldStatusLabel }}</span>
                    <span class="arrow">→</span>
                    <span class="new-status">{{ $newStatusLabel }}</span>
                </div>

                <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #eee;">
                    <small>
                        <strong>Date :</strong> {{ now()->format('d/m/Y H:i') }}
                    </small>
                </div>
            </div>

            <!-- Message personnalisé selon le statut -->
            @if($commande->status == 'ready_pickup')
                @if($commande->delivery_method == 'cargo')
                <div style="background: #e8f4fd; padding: 15px; border-radius: 5px; margin: 20px 0;">
                    <strong>Votre commande est prête pour retrait :</strong><br>
                    Votre commande est disponible chez nous. Vous pouvez la faire récupérer par votre cargo.
                    @if($commande->shipping_fee)
                        <br><small>Les frais de livraison sont de {{ number_format($commande->shipping_fee, 0, '.', ' ') }} XOF.</small>
                    @endif
                </div>
                @elseif($commande->delivery_method == 'tinda_awa')
                <div style="background: #fff9e6; padding: 15px; border-radius: 5px; margin: 20px 0;">
                    <strong>Préparation de la livraison :</strong><br>
                    Votre commande est prête pour l'expédition via Tinda Awa. Les frais de livraison vous seront communiqués sous peu.
                </div>
                @endif

            @elseif($commande->status == 'in_transit')
                <div style="background: #d1ecf1; padding: 15px; border-radius: 5px; margin: 20px 0;">
                    <strong>✈️ Votre commande est en route :</strong><br>
                    Votre commande a été expédiée et est en cours de livraison via Tinda Awa.
                    @if($commande->estimated_delivery)
                        <br>Livraison estimée : {{ \Carbon\Carbon::parse($commande->estimated_delivery)->format('d/m/Y') }}
                    @endif
                </div>

            @elseif($commande->status == 'delivered')
                <div style="background: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0;">
                    <strong>Livraison effectuée :</strong><br>
                    Votre commande a été livrée avec succès. Nous espérons que vous serez satisfait de votre achat !
                </div>

            @elseif($commande->status == 'picked_up')
                <div style="background: #e2e3e5; padding: 15px; border-radius: 5px; margin: 20px 0;">
                    <strong>Commande récupérée :</strong><br>
                    Votre commande a été récupérée par votre cargo. Elle sera bientôt en route vers vous.
                </div>

            @elseif($commande->status == 'cancelled')
                <div style="background: #f8d7da; padding: 15px; border-radius: 5px; margin: 20px 0;">
                    <strong>Commande annulée :</strong><br>
                    Votre commande a été annulée. Si vous avez des questions, n'hésitez pas à nous contacter.
                </div>
            @endif

            <p style="margin-top: 20px;">
                Vous pouvez suivre l'état de votre commande depuis votre espace client sur notre site.
            </p>

            <p>
                Cordialement,<br>
                <strong>L'équipe {{ config('app.name') }}</strong>
            </p>
        </div>

        <div class="footer">
            <p>
                {{ config('app.name') }}<br>
                <small>Pour toute question, contactez-nous à support@sumbaawa.com</small>
            </p>
        </div>
    </div>
</body>
</html>
