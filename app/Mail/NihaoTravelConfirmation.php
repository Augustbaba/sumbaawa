<?php

namespace App\Mail;

use App\Helpers\FrontHelper;
use App\Models\Travel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NihaoTravelConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $travel;
    public $subject;
    public $attachmentPaths;

    public function __construct(Travel $travel)
    {
        $this->travel = $travel;
        $this->subject = 'Confirmation de votre inscription Nihao Travel - Foire de Canton';
    }

    public function build()
    {
        return $this->subject($this->subject)
            ->markdown('emails.nihao-travel-confirmation')
            ->with([
                'travel' => $this->travel,
                'formattedAmount' => FrontHelper::format_currency($this->travel->amount_xof),
            ]);
    }
}
