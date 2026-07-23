<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\HomepageBlock;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Real named collections from the original site's own "Shop by Collection" grid and
        // hero banners — distinct real photography per collection, not generic product shots.
        $names = [
            ['name' => 'Hasli Collection', 'description' => 'Statement Hasli-style necklaces and sets.', 'image' => 'https://estele.co/cdn/shop/files/Hasli_Collection_Banner-2_jpg.jpg?width=1800'],
            ['name' => 'Sitara Collection', 'description' => 'Studded stars for everyday sparkle.', 'image' => 'https://estele.co/cdn/shop/files/Banner.jpg_2.jpg?width=1800'],
            ['name' => 'Rose Collection', 'description' => 'Our signature rose gold plating, in every category.', 'image' => 'https://estele.co/cdn/shop/files/Rose_Collection_22f60169-9004-4f13-b13c-4cf7e2a0e06b.jpg?width=1800'],
            ['name' => 'Crystal Blooms', 'description' => 'Floral crystal designs across the catalog.', 'image' => 'https://estele.co/cdn/shop/files/Crystal_Bloom_cade702c-fad5-49fa-a5dd-d4ef01106081.jpg?width=1800'],
            ['name' => 'Colour Pop', 'description' => 'Bold coloured stones for a playful edit.', 'image' => 'https://estele.co/cdn/shop/files/Coloure_Pop.jpg?width=1800'],
            ['name' => 'Mor Bagh Collection', 'description' => 'Peacock-inspired designs, festive and refined.', 'image' => 'https://estele.co/cdn/shop/files/Mor_Bagh.jpg?width=1800'],
            // Maharani Collection is a real named collection from the original's own COLLECTIONS
            // dropdown — its banner art was originally (mis)used for "Featured Collection"; correcting
            // that here rather than just deleting it, since it's real, correctly-labeled Estele art.
            ['name' => 'Maharani Collection', 'description' => 'Regal, statement pieces fit for royalty.', 'image' => 'https://estele.co/cdn/shop/files/Banner.jpg_1_54ef9678-0bf5-4964-a1a1-e441f615f57e.jpg?format=pjpg&v=1778837912&width=1800'],
            // Nav-only collections (not part of the "Shop by Collection" grid, same as the original) —
            // curated/query-based rather than a fixed even split, since these are editorial groupings,
            // not distinct product families, so overlap with the collections above is expected.
            // Banner images are real product photos taken directly from the original's own New
            // Arrivals/Bestsellers carousels (not a mismatched other-collection's branded banner) —
            // rendered with object-contain since these are square product shots, not wide banner art.
            ['name' => 'New Arrivals', 'description' => 'The latest additions to the catalog.', 'image' => 'https://estele.co/cdn/shop/files/Untitled-1_1d5591b5-9b5f-4016-860a-22d372f3530a.jpg?width=1200', 'query' => fn () => Product::where('is_active', true)->latest()->take(10)->pluck('id')],
            ['name' => 'Wedding Season', 'description' => 'Bridal-ready pieces for the wedding season.', 'image' => 'https://estele.co/cdn/shop/files/WEDDING_jpg.jpg?width=1800', 'query' => fn () => Product::where('is_active', true)->whereHas('categories', fn ($q) => $q->whereIn('slug', ['choker-sets', 'mangalsutra', 'necklace-sets']))->pluck('id')],
            ['name' => 'Best Seller', 'description' => 'Our most-loved pieces, hand-picked by the team.', 'image' => 'https://estele.co/cdn/shop/files/01_09e3acd8-c775-4737-83af-365328aec27f.jpg?width=1200', 'query' => fn () => Product::where('is_active', true)->where('is_featured', true)->pluck('id')],
        ];

        // Remove any collections from an earlier, differently-scoped seed pass.
        $keepSlugs = collect($names)->map(fn ($n) => Str::slug($n['name']));
        Collection::whereNotIn('slug', $keepSlugs)->get()->each(fn (Collection $c) => $c->delete());

        // The first 6 (real product "families") get an even, non-overlapping split; the last 3
        // (editorial nav-only groupings) get their own real query instead, overlap allowed.
        $partitionedNames = array_filter($names, fn ($n) => ! isset($n['query']));
        $productIds = Product::where('is_active', true)->orderBy('id')->pluck('id');
        $chunks = $productIds->chunk((int) ceil($productIds->count() / count($partitionedNames)))->values();

        foreach ($names as $index => $data) {
            $collection = Collection::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'sort_order' => $index,
                    'is_active' => true,
                ]
            );

            if (! $collection->hasMedia('image')) {
                try {
                    $collection->addMediaFromUrl($data['image'])
                        ->usingFileName($collection->slug.'.jpg')
                        ->toMediaCollection('image');
                } catch (\Throwable $e) {
                    $this->command?->warn("Could not fetch collection image for {$data['name']}: {$e->getMessage()}");
                }
            }

            $productIdsForCollection = isset($data['query']) ? $data['query']() : $chunks->get($index, collect());
            $collection->products()->sync($productIdsForCollection);
        }

        // The original's "Shop by Collection" grid only ever featured these 4 — Hasli/Sitara
        // were promoted via the hero banner + top-level nav only, never duplicated into this grid.
        $gridSlugs = ['rose-collection', 'crystal-blooms', 'colour-pop', 'mor-bagh-collection'];
        $collections = Collection::whereIn('slug', $gridSlugs)->orderBy('sort_order')->get();

        $block = HomepageBlock::firstOrCreate(
            ['type' => 'collection_carousel'],
            ['title' => 'Shop by Collection', 'sort_order' => 20, 'is_active' => true]
        );

        $block->items()->delete();
        foreach ($collections as $index => $collection) {
            $block->items()->create([
                'itemable_type' => Collection::class,
                'itemable_id' => $collection->id,
                'sort_order' => $index,
            ]);
        }
    }
}
