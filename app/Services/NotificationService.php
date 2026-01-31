<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification;
use App\Models\Commande;
use App\Helpers\FrontHelper;

class NotificationService
{
    /**
     * Notifier le changement de statut d'une commande
     */
    public function notifyOrderStatusUpdate(Commande $commande, $oldStatus)
    {
        $user = $commande->user;
        $statusLabels = [
            'pending' => 'En attente',
            'processing' => 'En cours de traitement',
            'ready_pickup' => 'Prêt pour retrait',
            'picked_up' => 'Récupéré par le cargo',
            'in_transit' => 'En cours de livraison',
            'arrived' => 'Arrivé à destination',
            'delivered' => 'Livré',
            'cancelled' => 'Annulé'
        ];

        $oldStatusLabel = $statusLabels[$oldStatus] ?? $oldStatus;
        $newStatusLabel = $commande->status_label;

        $title = "Mise à jour de votre commande #{$commande->code}";
        $message = "Le statut de votre commande a changé de \"{$oldStatusLabel}\" à \"{$newStatusLabel}\"";

        // Message personnalisé selon le statut
        $additionalMessage = $this->getStatusMessage($commande, $user);
        if ($additionalMessage) {
            $message .= "\n\n" . $additionalMessage;
        }

        $data = [
            'commande_id' => $commande->id,
            'commande_code' => $commande->code,
            'old_status' => $oldStatus,
            'new_status' => $commande->status,
            'old_status_label' => $oldStatusLabel,
            'new_status_label' => $newStatusLabel,
            'delivery_method' => $commande->delivery_method,
            'shipping_fee' => $commande->shipping_fee,
            'shipping_fee_formatted' => FrontHelper::format_amount_for_user($commande->shipping_fee, $user),
            'estimated_delivery' => $commande->estimated_delivery,
            'url' => route('user.orders.show', $commande->code)
        ];

        return $user->notifie('order_status_update', $title, $message, $data);
    }

    /**
     * Notifier les frais de livraison
     */
    public function notifyShippingFee(Commande $commande)
    {
        $user = $commande->user;

        $title = "Frais de livraison - Commande #{$commande->code}";
        $shippingFeeFormatted = FrontHelper::format_amount_for_user($commande->shipping_fee, $user);
        $message = "Des frais de livraison de {$shippingFeeFormatted} ont été définis pour votre commande.";

        if ($commande->delivery_method == 'tinda_awa') {
            $message .= "\n\nVotre commande sera livrée via Tinda Awa.";
            if ($commande->estimated_delivery) {
                $message .= "\nLivraison estimée : " . \Carbon\Carbon::parse($commande->estimated_delivery)->format('d/m/Y');
            }
        } else {
            $message .= "\n\nVotre commande est prête à être expédiée à votre cargo.";
        }

        $message .= "\n\nVous pouvez payer ces frais depuis votre espace client.";

        $data = [
            'commande_id' => $commande->id,
            'commande_code' => $commande->code,
            'shipping_fee' => $commande->shipping_fee,
            'shipping_fee_formatted' => $shippingFeeFormatted,
            'delivery_method' => $commande->delivery_method,
            'estimated_delivery' => $commande->estimated_delivery,
            'payment_url' => route('shipping.payment.show', $commande->code),
            'url' => route('user.orders.show', $commande->code)
        ];

        return $user->notifie('shipping_fee', $title, $message, $data);
    }

    /**
     * Notifier le paiement réussi des frais de livraison
     */
    public function notifyShippingPaymentSuccess(Commande $commande)
    {
        $user = $commande->user;

        $title = "Paiement confirmé - Frais de livraison #{$commande->code}";
        $shippingFeeFormatted = FrontHelper::format_amount_for_user($commande->shipping_fee, $user);
        $message = "Votre paiement de {$shippingFeeFormatted} pour les frais de livraison a été confirmé.";

        if ($commande->delivery_method == 'tinda_awa') {
            $message .= "\n\nVotre commande va maintenant être expédiée via Tinda Awa.";
            if ($commande->estimated_delivery) {
                $message .= "\nLivraison estimée : " . \Carbon\Carbon::parse($commande->estimated_delivery)->format('d/m/Y');
            }
        } else {
            $message .= "\n\nVotre commande est maintenant disponible pour être récupérée par votre cargo.";
        }

        $data = [
            'commande_id' => $commande->id,
            'commande_code' => $commande->code,
            'shipping_fee' => $commande->shipping_fee,
            'shipping_fee_formatted' => $shippingFeeFormatted,
            'shipping_payment_id' => $commande->shipping_payment_id,
            'delivery_method' => $commande->delivery_method,
            'estimated_delivery' => $commande->estimated_delivery,
            'url' => route('user.orders.show', $commande->code)
        ];

        return $user->notifie('shipping_payment_success', $title, $message, $data);
    }

    /**
     * Notifier la réception de la commande
     */
    public function notifyOrderReceived(Commande $commande)
    {
        $user = $commande->user;

        $title = "Commande récupérée - #{$commande->code}";
        $message = "Vous avez confirmé avoir récupéré votre commande #{$commande->code}.";
        $message .= "\n\nNous espérons que vous êtes satisfait de votre achat !";

        $data = [
            'commande_id' => $commande->id,
            'commande_code' => $commande->code,
            'received_at' => $commande->received_at,
            'url' => route('user.orders.show', $commande->code)
        ];

        return $user->notifie('order_received', $title, $message, $data);
    }

    /**
     * Message personnalisé selon le statut
     */
    private function getStatusMessage(Commande $commande, User $user)
    {
        $shippingFeeFormatted = $commande->shipping_fee
            ? FrontHelper::format_amount_for_user($commande->shipping_fee, $user)
            : null;

        $messages = [
            'ready_pickup' => [
                'cargo' => "Votre commande est disponible chez nous. Vous pouvez la faire récupérer par votre cargo." .
                          ($shippingFeeFormatted ? "\nLes frais de livraison sont de {$shippingFeeFormatted}." : ""),
                'tinda_awa' => "Votre commande est prête pour l'expédition via Tinda Awa." .
                              ($shippingFeeFormatted ? "\nLes frais de livraison sont de {$shippingFeeFormatted}." :
                               "\nLes frais de livraison vous seront communiqués sous peu.")
            ],
            'in_transit' => "Votre commande a été expédiée et est en cours de livraison via Tinda Awa." .
                           ($commande->estimated_delivery ? "\nLivraison estimée : " . \Carbon\Carbon::parse($commande->estimated_delivery)->format('d/m/Y') : ""),
            'delivered' => "Votre commande a été livrée avec succès. Nous espérons que vous serez satisfait de votre achat !",
            'picked_up' => "Votre commande a été récupérée par votre cargo. Elle sera bientôt en route vers vous.",
            'cancelled' => "Votre commande a été annulée. Si vous avez des questions, n'hésitez pas à nous contacter."
        ];

        if (isset($messages[$commande->status])) {
            if (is_array($messages[$commande->status])) {
                return $messages[$commande->status][$commande->delivery_method] ?? $messages[$commande->status]['default'] ?? '';
            }
            return $messages[$commande->status];
        }

        return '';
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead(User $user)
    {
        return $user->notifications()
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
    }

    /**
     * Supprimer les anciennes notifications
     */
    public function cleanupOldNotifications($days = 90)
    {
        return Notification::where('created_at', '<', now()->subDays($days))->delete();
    }
}
