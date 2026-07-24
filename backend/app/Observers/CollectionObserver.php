<?php

namespace App\Observers;

use App\Models\Collection;
use Illuminate\Support\Facades\Cache;

class CollectionObserver
{
    public function saved(Collection $collection): void
    {
        Cache::forget('site.nav_collections');
        Cache::tags(['home'])->flush();
    }

    public function deleted(Collection $collection): void
    {
        Cache::forget('site.nav_collections');
        Cache::tags(['home'])->flush();
    }
}
