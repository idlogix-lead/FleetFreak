<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        $htmlContent = "
            <html>
            <head>
                <title>Password Reset OTP</title>
            </head>
            <body>
                <p>Your OTP for password reset is: {$this->otp}</p>
                <p>This OTP will expire in 15 minutes.</p>
            </body>
            </html>
        ";

        return $this->html($htmlContent)
                    ->subject('Your Password Reset OTP');
    }
}
