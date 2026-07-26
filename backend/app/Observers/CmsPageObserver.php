<?php

namespace App\Observers;

use App\Models\CmsPage;
use Illuminate\Support\Facades\Cache;

class CmsPageObserver
{
    public function saved(CmsPage $cmsPage): void
    {
        Cache::tags(['cms'])->flush();
    }

    public function deleted(CmsPage $cmsPage): void
    {
        Cache::tags(['cms'])->flush();
    }
}
