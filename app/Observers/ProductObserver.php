<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        Cache::forget('products.index');

        Log::info('Product created', [
            'product_id' => $product->id,
            'name' => $product->name,
            'by_user_id' => Auth::id(),
        ]);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        Cache::forget('products.index');

        Log::info('Product updated', [
            'product_id' => $product->id,
            'name' => $product->name,
            'by_user_id' => Auth::id(),
        ]);
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        Cache::forget('products.index');

        Log::info('Product deleted', [
            'product_id' => $product->id,
            'name' => $product->name,
            'by_user_id' => Auth::id(),
        ]);
    }

}
