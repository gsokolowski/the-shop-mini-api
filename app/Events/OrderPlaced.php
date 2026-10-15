<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// OrderPlaced class defines an OrderPlaced event that 
// carries an Order so listeners can react when an order is placed.
class OrderPlaced
{
    // Dispatchable — lets you fire it with OrderPlaced::dispatch($order) 
    use Dispatchable, SerializesModels;
    
    public Order $order;

    public function __construct(Order $order) {
        $this->order = $order;
    }
    
    // or with promotion
    //public function __construct(public Order $order) {}

}