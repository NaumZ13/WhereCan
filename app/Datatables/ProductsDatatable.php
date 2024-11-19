<?php 

namespace App\Datatables;

use App\Models\Product;
use Illuminate\Support\Collection;

class ProductsDatatable {

    public function getData()
    {
        $client = auth()->user()->client;
        $isPublished = request()->filled('filters.showUnpublished') ? request('filters.showUnpublished') !== 'true' : true;

        $query = Product::where('client_id', $client->id)
            ->where('is_published', $isPublished)
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.name as category_name');

        if(request()->has('filters.search') && request('filters.search') != '') {
            $query->where('products.name', 'like', '%' . request('filters.search') . '%')
                ->orWhere('categories.name', 'like', '%' . request('filters.search') . '%');
        }

        return $query->paginate(10);
    }

    public function getColumns(): array
    {
        return [
            ['key' => 'id', 'label' => 'ID', 'sortable' => true],
            ['key' => 'category_name', 'label' => 'Category', 'sortable' => true],
            ['key' => 'name', 'label' => 'Name', 'sortable' => true],
            ['key' => 'price', 'label' => 'Price', 'sortable' => true],
            ['key' => 'created_at', 'label' => 'Created At', 'sortable' => true],
            ['key' => 'actions', 'label' => 'Actions']
        ];
    }
}