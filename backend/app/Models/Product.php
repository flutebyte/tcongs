<?php

namespace App\Models;

use App\Models\Concerns\HasSeoMeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasSeoMeta;
    use InteractsWithMedia;
    use Searchable;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery')
            ->useDisk('original_images')
            ->storeConversionsOnDisk('public');
    }

    public function registerMediaConversions(?\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        // Sizes/quality follow the responsive image spec (§3.1).
        $this->addMediaConversion('card')->width(400)->format('webp')->quality(78);
        $this->addMediaConversion('mobile')->width(768)->format('webp')->quality(80);
        $this->addMediaConversion('tablet')->width(1024)->format('webp')->quality(82);
        $this->addMediaConversion('detail')->width(1600)->format('webp')->quality(83);
    }

    protected $fillable = [
        'title',
        'slug',
        'sku',
        'description',
        'price',
        'compare_at_price',
        'stock_quantity',
        'is_active',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_at_price' => 'decimal:2',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class);
    }

    public function searchableAs(): string
    {
        return 'products';
    }

    public function shouldBeSearchable(): bool
    {
        return $this->is_active;
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'sku' => $this->sku,
            'price' => (float) $this->price,
            'is_featured' => $this->is_featured,
            'categories' => $this->categories->pluck('name')->all(),
        ];
    }
}
