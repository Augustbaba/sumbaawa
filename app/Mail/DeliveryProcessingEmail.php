<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Commande;

class DeliveryProcessingEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $deliveryData;

    public function __construct(Commande $order, $deliveryData = null)
    {
        $this->order = $order;
        $this->deliveryData = $deliveryData;
    }

    public function build()
    {
        $subject = 'Processus de livraison - Commande #' . $this->order->code;

        return $this->subject($subject)
                    ->view('emails.delivery-processing')
                    ->with([
                        'order' => $this->order,
                        'deliveryData' => $this->deliveryData,
                        'user' => $this->order->user
                    ]);
    }
}
