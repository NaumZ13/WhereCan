<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ReplyResource;
use App\Http\Resources\ReviewResource;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('Products/Index', [
            'products' => ProductResource::collection(Product::paginate(12))
        ]);
    }

    public function search(Request $request)
    {
        $products = Product::filter($request->only('search'))
            ->paginate(12)
            ->appends($request->all());

            return response()->json([
                'products' => ProductResource::collection($products),
                'meta' => [
                    // 'links' => $products->links(),
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'total' => $products->total(),
                    // 'per_page' => $products->perPage(),
                ]
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $reviews = ReviewResource::collection(
            $product->reviews()->with(['user', 'replies.user'])->latest()->paginate(10)
        );
        $reviews->collection->transform(fn ($resource) => $resource->withLikePermission());

        return inertia('Products/Show', [
            'product' => fn () => ProductResource::make($product),
            'reviews' => $reviews,
        ]);
    }
}
