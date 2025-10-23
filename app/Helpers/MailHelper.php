<?php

namespace App\Helpers;

use Log;
use Illuminate\Support\Facades\Mail;

class MailHelper
{
    /**
     * Send a simple email.
     */
    public static function sendMail($to, $subject, $message)
    {
        try {
            Mail::raw($message, function ($mail) use ($to, $subject) {
                $mail->to($to)
                     ->subject($subject);
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Mail send failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
