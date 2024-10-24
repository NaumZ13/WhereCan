<?php

namespace App\Http\Controllers;

use App\Http\Requests\Products\StoreRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ClientProductsController extends Controller
{
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
}
