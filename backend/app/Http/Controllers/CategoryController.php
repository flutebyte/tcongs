<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('sort_order')->get();

        return view('categories.index', compact('categories'));
    }

    public function show(Category $category, Request $request)
    {
        $sort = $request->query('sort', 'featured');

        $query = $category->products()->where('is_active', true);

        match ($sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'newest' => $query->latest('products.created_at'),
            default => $query->orderBy('products.id'),
        };

        $products = $query->paginate(24)->withQueryString();

        return view('categories.show', compact('category', 'products', 'sort'));
    }
}
