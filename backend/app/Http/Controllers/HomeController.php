<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\HomepageBlock;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $data = Cache::tags(['home'])->remember('home.page', now()->addHour(), function () {
            return [
                'categories' => Category::orderBy('sort_order')->get(),
                'banners' => Banner::active()->ordered()->get(),
                'homepageBlocks' => HomepageBlock::active()
                    ->ordered()
                    ->with(['items.itemable'])
                    ->get(),
            ];
        });

        return view('home.index', $data);
    }
}
