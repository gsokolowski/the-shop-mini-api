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

class OrderController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        // Orders for the logged-in user 
        $orders = $request->user()
            ->orders()
            ->with('product') // eager load product for each order
            ->latest()
            ->get();

        return OrderResource::collection($orders);
    }

    public function store(StoreOrderRequest $request): OrderResource
    {
        $order = DB::transaction(function () use ($request) {
            $product = Product::query()
                ->lockForUpdate()
                ->findOrFail($request->validated('product_id'));

            if ($product->stock < 1) {
                abort(422, 'Product out of stock.');
            }

            $product->decrement('stock');
            // updates $product->stock in memory too.
            // update `products` set `stock` = `stock` - 1 where `id` = ?;
            // ProductObserver on update clears products.index cache


            // Insert order to DB:orders
            return Order::query()->create([
                'user_id' => $request->user()->id,
                'product_id' => $product->id,
                'price' => $product->price,
            ]);
        });

        // Later: event(new \App\Events\OrderPlaced($order));

        return new OrderResource($order->load('product'));
    }
}