<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserAccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    public $loginUrl;

    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
        $this->loginUrl = url('/login');
    }

    public function build()
    {
        return $this->subject('Votre compte a été créé')
                    ->markdown('emails.account-created')
                    ->with([
                        'user' => $this->user,
                        'password' => $this->password,
                        'loginUrl' => $this->loginUrl,
                    ]);
    }
}
