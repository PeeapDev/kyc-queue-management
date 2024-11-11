<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SetupPasswordNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $setupUrl;

    public function __construct($setupUrl)
    {
        $this->setupUrl = $setupUrl;
    }

    public function build()
    {
        return $this->markdown('emails.setup-password')
                    ->subject('Set Up Your Account Password');
    }
}
