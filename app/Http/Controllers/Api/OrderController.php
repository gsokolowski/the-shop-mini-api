<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class OrderController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $orders = $request->user()
            ->orders()
            ->with('product')
            ->latest()
            ->get();

        return OrderResource::collection($orders);
    }

    public function store(StoreOrderRequest $request): AnonymousResourceCollection
    {
        $orders = DB::transaction(function () use ($request) {
            $created = [];

            foreach ($request->validated('product_ids') as $productId) {
                //get product X for update, safely, inside this transaction.
                // lockForUpdate
                $product = Product::query()
                    ->lockForUpdate()
                    ->findOrFail($productId);

                if ($product->stock < 1) {
                    abort(422, "Product {$product->id} is out of stock.");
                }

                $product->decrement('stock');
                // updates $product->stock in memory too.
                // update `products` set `stock` = `stock` - 1 where `id` = ?;
                // ProductObserver on update clears products.index cache

                $created[] = Order::query()->create([
                    'user_id' => $request->user()->id,
                    'product_id' => $product->id,
                    'price' => $product->price,
                ]);
            }

            // turn the PHP array of Order models into a Eloquent Collection and 
            // eager-load the product relation on every order in that collection (one query for all related products, instead of N+1)
            return (new EloquentCollection($created))->load('product');
        });

        return OrderResource::collection($orders);
    }
}