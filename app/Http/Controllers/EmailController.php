<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public static function sendEmail($content, $to, $title, $subject, $cc)
    {
        Mail::send('email.template', ['title' => $title, 'content' => $content], function ($message) use ($to, $subject, $cc) {
            $message->from('projeto100in.estg@ipleiria.pt', 'Plataforma 100%IN');
            $message->sender('projeto100in.estg@ipleiria.pt', 'Plataforma 100%IN');
            $message->to($to);
            $message->cc($cc);
            $message->subject($subject);
        });
    }

    // Como chamar a função 
    //EmailController::sendEmail('Content', '2160852@my.ipleiria.pt', 'Title', 'Subject', '2161353@my.ipleiria.pt');

}
