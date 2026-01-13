<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Commande;

class OrderConfirmationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Commande $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('Confirmation de votre commande #' . $this->order->code)
                    ->view('emails.order-confirmation')
                    ->with([
                        'order' => $this->order,
                        'user' => $this->order->user
                    ]);
    }
}
