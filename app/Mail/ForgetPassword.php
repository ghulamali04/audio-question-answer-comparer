<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgetPassword extends Mailable
{
    use Queueable, SerializesModels;
    public $get_user_token;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($get_user_token)
    {
        $this->get_user_token = $get_user_token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('laravelmailer909@gmail.com','You Have Requested Password Change')->view('main.forgetPassword');
    }
}
