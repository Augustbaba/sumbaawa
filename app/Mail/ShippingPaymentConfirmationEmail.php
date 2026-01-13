<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Commande;

class ShippingPaymentConfirmationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $commande;

    public function __construct(Commande $commande)
    {
        $this->commande = $commande;
    }

    public function build()
    {
        $subject = 'Confirmation paiement frais de livraison - Commande #' . $this->commande->code;

        return $this->subject($subject)
                    ->view('emails.shipping-payment-confirmation')
                    ->with([
                        'commande' => $this->commande,
                        'user' => $this->commande->user
                    ]);
    }
}
