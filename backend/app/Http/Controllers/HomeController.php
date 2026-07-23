<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\HomepageBlock;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('sort_order')->get();

        $banners = Banner::active()->ordered()->get();

        $homepageBlocks = HomepageBlock::active()
            ->ordered()
            ->with(['items.itemable'])
            ->get();

        return view('home.index', compact('categories', 'banners', 'homepageBlocks'));
    }
}
