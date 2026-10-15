<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Mail;

// This listener runs when an OrderPlaced event is fired.
class SendOrderConfirmation
{
    public function handle(OrderPlaced $event): void
    {
        //  It loads the order’s user and product relations if they aren’t already loaded.
        $order = $event->order->loadMissing(['user', 'product']);

        Mail::to($order->user->email)
        //Then it queues an OrderConfirmationMail to the order user’s email.
            ->queue(new OrderConfirmationMail($order));
    }
}