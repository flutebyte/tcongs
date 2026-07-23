<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->query('q', ''));

        if ($query === '') {
            $products = new LengthAwarePaginator([], 0, 24);
        } else {
            // Laravel Scout + MeiliSearch (spec §2/§5) — shouldBeSearchable() on Product
            // already restricts the index to is_active products, so no extra where() needed.
            $products = Product::search($query)
                ->paginate(24)
                ->withQueryString();
        }

        return view('search.index', compact('products', 'query'));
    }
}
