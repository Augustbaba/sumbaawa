<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Commande;

class OrderStatusUpdateEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $commande;
    public $oldStatus;
    public $statusLabels;

    public function __construct(Commande $commande, $oldStatus)
    {
        $this->commande = $commande;
        $this->oldStatus = $oldStatus;

        $this->statusLabels = [
            'pending' => 'En attente',
            'processing' => 'En cours de traitement',
            'ready_pickup' => 'Prêt pour retrait',
            'picked_up' => 'Récupéré par le cargo',
            'in_transit' => 'En cours de livraison',
            'arrived' => 'Arrivé à destination',
            'delivered' => 'Livré',
            'cancelled' => 'Annulé'
        ];
    }

    public function build()
    {
        $subject = 'Mise à jour statut - Commande #' . $this->commande->code;

        return $this->subject($subject)
                    ->view('emails.order-status-update')
                    ->with([
                        'commande' => $this->commande,
                        'user' => $this->commande->user,
                        'oldStatusLabel' => $this->statusLabels[$this->oldStatus] ?? $this->oldStatus,
                        'newStatusLabel' => $this->commande->status_label
                    ]);
    }
}
