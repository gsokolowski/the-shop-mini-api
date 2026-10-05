<?php

// app/Http/Controllers/Api/ProductController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        
        // check Cache for key products.index
        if (Cache::has('products.index')) {
            $products = Cache::get('products.index');
        } else {
            // get all products
            $products = Product::query()->latest()->get();
            // cash key products.index and TTL — expires after 10 minutes
            Cache::put('products.index', $products, now()->addMinutes(10));
        }

        //or in short  the same way
        // Cache::remember(...) is just a short way to write the first version.
        // $products = Cache::remember('products.index', now()->addMinutes(10), function () {
        //     return Product::query()->latest()->get();
        // });

        // ProductResource is used for returning specific columns from $prouct collection
        return ProductResource::collection($products);
    }

    public function show(string $slug): ProductResource
    {
        // pick product by slug
        $product = Product::query()->where('slug', $slug)->firstOrFail();

        // again Porduct Resource is used
        return new ProductResource($product);
    }
}
