<?php 

namespace App\Datatables;

use App\Models\Product;
use Illuminate\Support\Collection;

class ProductsDatatable {

    public function getData(): Collection
    {
        $client = auth()->user()->client;
        return Product::select('id', 'name', 'price', 'created_at')->where('client_id', $client->id)->get();
    }

    public function getColumns(): array
    {
        return [
            ['key' => 'id', 'label' => 'ID', 'sortable' => true],
            ['key' => 'name', 'label' => 'Name', 'sortable' => true],
            ['key' => 'price', 'label' => 'Price', 'sortable' => true],
            ['key' => 'created_at', 'label' => 'Created At', 'sortable' => true],
            ['key' => 'actions', 'label' => 'Actions']
        ];
    }
}