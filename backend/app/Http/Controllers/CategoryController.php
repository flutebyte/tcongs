<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Cache::tags(['home'])->remember('categories.index', now()->addHour(), function () {
            return Category::orderBy('sort_order')->get();
        });

        return view('categories.index', compact('categories'));
    }

    public function show(Category $category, Request $request)
    {
        $sort = $request->query('sort', 'featured');
        $page = $request->query('page', 1);

        $cacheKey = "category.{$category->id}.products.{$sort}.{$page}";

        $products = Cache::tags(['category:' . $category->id])->remember(
            $cacheKey,
            now()->addMinutes(15),
            function () use ($category, $sort) {
                $query = $category->products()->where('is_active', true);

                match ($sort) {
                    'price_asc' => $query->orderBy('price'),
                    'price_desc' => $query->orderBy('price', 'desc'),
                    'newest' => $query->latest('products.created_at'),
                    default => $query->orderBy('products.id'),
                };

                return $query->paginate(24)->withQueryString();
            }
        );

        return view('categories.show', compact('category', 'products', 'sort'));
    }
}
