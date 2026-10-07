<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// This is a Laravel Mailable: a class that defines a welcome email. 
// It isn’t sent by itself — something else 
// must call Mail::to($user)->send(new WelcomeMail($user)) (or queue).
// in our case it will be queue
class WelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    //constructor property promotion
    public function __construct(public User $user) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to The Shop',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.welcome',
        );
    }
}