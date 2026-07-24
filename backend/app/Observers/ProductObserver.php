<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductObserver
{
    public function saved(Product $product): void
    {
        $this->flush($product);
    }

    public function updated(Product $product): void
    {
        $this->flush($product);
    }

    public function deleted(Product $product): void
    {
        $this->flush($product);
    }

    private function flush(Product $product): void
    {
        Cache::tags(['product:' . $product->id])->flush();

        foreach ($product->categories()->pluck('categories.id') as $categoryId) {
            Cache::tags(['category:' . $categoryId])->flush();
        }

        if ($product->is_featured) {
            Cache::tags(['home'])->flush();
        }
    }
}
