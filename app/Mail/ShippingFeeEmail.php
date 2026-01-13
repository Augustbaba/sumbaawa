<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Commande;

class ShippingFeeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $commande;
    public $paymentLink;

    public function __construct(Commande $commande)
    {
        $this->commande = $commande;
        $this->paymentLink = url('/pay-shipping/' . $commande->code);
    }

    public function build()
    {
        $subject = 'Frais de livraison - Commande #' . $this->commande->code;

        return $this->subject($subject)
                    ->view('emails.shipping-fee')
                    ->with([
                        'commande' => $this->commande,
                        'user' => $this->commande->user,
                        'paymentLink' => $this->paymentLink
                    ]);
    }
}
