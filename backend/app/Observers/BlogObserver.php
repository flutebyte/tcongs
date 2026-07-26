<?php

namespace App\Observers;

use App\Models\Blog;
use Illuminate\Support\Facades\Cache;

class BlogObserver
{
    public function saved(Blog $blog): void
    {
        Cache::tags(['blog'])->flush();
    }

    public function deleted(Blog $blog): void
    {
        Cache::tags(['blog'])->flush();
    }
}
