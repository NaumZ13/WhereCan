<?php

namespace App\Http\Controllers;

use App\Datatables\ProductsDatatable;
use App\Http\Requests\Products\StoreRequest;
use App\Http\Requests\Products\UpdateRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientProductsController extends Controller
{
    public function index()
    {
        return inertia('Clients/Products/Index');
    }

    public function create()
    {
        return inertia('Clients/Products/Create');
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $client = auth()->user()->client;

        Product::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'client_id' => $client->id,
            'is_published' => true,
        ]);

        return to_route('client.dashboard')->with('success', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        return inertia('Clients/Products/Edit', [
            'product' => $product,
        ]);
    }

    public function update(UpdateRequest $request, Product $product)
    {
        $data = $request->validated();

        $product->update($data);

        return to_route('client.products.index')->with('success', 'Product updated successfully');
    }

    public function getProducts(): JsonResponse
    {
        $datatable = new ProductsDatatable();
        
        return response()->json([
            'data' => $datatable->getData(),
            'columns' => $datatable->getColumns(),
        ]);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return to_route('client.products.index')->with('success', 'Product deleted successfully');
    }
}
