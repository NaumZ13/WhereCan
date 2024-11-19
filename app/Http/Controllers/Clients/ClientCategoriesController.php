<?php

namespace App\Http\Controllers\Clients;

use App\Datatables\CategoriesDatatable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientCategoriesController extends Controller
{
    public function index()
    {
        return inertia('Clients/Categories/Index');
    }

    public function getCategories(): JsonResponse
    {
        $datatable = new CategoriesDatatable();
        
        return response()->json([
            'data' => $datatable->getData(),
            'columns' => $datatable->getColumns(),
        ]);
    }


    public function create()
    {
        $categories = Category::all();

        return inertia('Clients/Categories/Create', [
            'categories' => $categories,
        ]);
    }    

    public function store(Request $request) 
    {
        $data = $request->validate([
            'name' => 'required|string|min:2|max:40|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // $client = auth()->user()->client; // TO DO, MAYBE NOT SURE HOW TO APPROACH THIS!

        Category::create([
            'name' => $data['name'],
            'parent_id' => $data['parent_id'],
        ]);

        return to_route('client.dashboard')->with('success', 'Category created successfully');
    }
}
