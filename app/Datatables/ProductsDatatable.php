<?php 

namespace App\Datatables;

use App\Models\Product;
use Illuminate\Support\Collection;

class ProductsDatatable {

    public function getData()
    {
        $client = auth()->user()->client;
        $isPublished = true;

        if(request()->has('filters.showUnpublished') && request('filters.showUnpublished') === 'true') {
           $isPublished = false;
        } 
        
        $query = Product::select('id', 'name', 'price', 'created_at')->where('client_id', $client->id)->where('is_published', $isPublished);
        
        if(request()->has('filters.search') && request('filters.search') != '') {
            $query->where('name', 'like', '%' . request('filters.search') . '%');
        }

        return $query->paginate(10);
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