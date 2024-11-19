<?php 

namespace App\Datatables;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;

class CategoriesDatatable {

    public function getData()
    {
        // $client = auth()->user()->client;
        // $isPublished = request()->filled('filters.showUnpublished') ? request('filters.showUnpublished') !== 'true' : true;

        $query = Category::select('categories.*');

        if(request()->has('filters.search') && request('filters.search') != '') {
            $query->where('name', 'like', '%' . request('filters.search') . '%');
        }

        return $query->paginate(10);
    }

    public function getColumns(): array
    {
        return [
            ['key' => 'id', 'label' => 'ID', 'sortable' => true],
            ['key' => 'name', 'label' => 'Category', 'sortable' => true],
        ];
    }
}