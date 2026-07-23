<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Category;
use App\Models\HomepageBlock;
use App\Models\Product;
use Illuminate\Database\Seeder;

class HomepageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedBanners();
        $this->seedProductCarousel();
        $this->seedCelebrities();
        $this->seedUsp();
        $this->seedTestimonials();
        $this->seedBrandStory();
        $this->seedNewsletter();
    }

    private function seedBanners(): void
    {
        if (Banner::count() > 0) {
            return;
        }

        // Real wide-format banner photography from the original site's hero carousel —
        // distinct from product/category photos, purpose-shot at 1800x700.
        $slides = [
            ['slug' => 'grand-sale', 'title' => 'Grand Sale', 'image' => 'https://estele.co/cdn/shop/files/2612-1080.jpg_2.jpg?width=1800'],
            ['slug' => 'hasli', 'title' => 'Hasli Collection', 'image' => 'https://estele.co/cdn/shop/files/Hasli_Collection_Banner-2_jpg.jpg?width=1800'],
            ['slug' => 'sitara', 'title' => 'Sitara Collection', 'image' => 'https://estele.co/cdn/shop/files/Banner.jpg_2.jpg?width=1800'],
            ['slug' => 'wedding-season', 'title' => 'Wedding Season', 'image' => 'https://estele.co/cdn/shop/files/WEDDING_jpg.jpg?width=1800'],
            ['slug' => 'maharani', 'title' => 'Maharani Collection', 'image' => 'https://estele.co/cdn/shop/files/Banner.jpg_1_54ef9678-0bf5-4964-a1a1-e441f615f57e.jpg?format=pjpg&v=1778837912&width=1800'],
        ];

        foreach ($slides as $index => $slide) {
            $banner = Banner::create([
                'title' => $slide['title'],
                'link_url' => route('home'),
                'sort_order' => $index,
                'is_active' => true,
            ]);

            try {
                $banner->addMediaFromUrl($slide['image'])
                    ->usingFileName('banner-'.$slide['slug'].'.jpg')
                    ->toMediaCollection('image');
            } catch (\Throwable $e) {
                $this->command?->warn("Could not fetch banner image for {$slide['title']}: {$e->getMessage()}");
            }
        }
    }

    private function seedProductCarousel(): void
    {
        if (HomepageBlock::where('type', 'product_carousel')->exists()) {
            return;
        }

        $block = HomepageBlock::create([
            'type' => 'product_carousel',
            'title' => 'Featured',
            'subtitle' => 'Handpicked pieces from across our collections.',
            'cta_label' => 'View All',
            'cta_url' => null,
            'sort_order' => 10,
            'is_active' => true,
        ]);

        Product::where('is_featured', true)->orderBy('id')->take(10)->get()
            ->each(function (Product $product, int $index) use ($block) {
                $block->items()->create([
                    'itemable_type' => Product::class,
                    'itemable_id' => $product->id,
                    'sort_order' => $index,
                ]);
            });
    }

    private function seedCelebrities(): void
    {
        if (HomepageBlock::where('type', 'celebrities')->exists()) {
            return;
        }

        $block = HomepageBlock::create([
            'type' => 'celebrities',
            'subtitle' => 'Glamour meets grace — worn by the stars, made for you.',
            'cta_label' => 'Shop Collection',
            'cta_url' => route('home'),
            'sort_order' => 30,
            'is_active' => true,
        ]);

        // Real celebrity endorsement photography from the original site's "As Seen On" section.
        $celebrities = [
            ['name' => 'Divyanka', 'image' => 'https://estele.co/cdn/shop/files/group-85141-2x-d643c9e8-3545-4cff-a23b-6622627ac982-68c29bd9bf857.webp?width=600'],
            ['name' => 'Jannat Zubair Rahmani', 'image' => 'https://estele.co/cdn/shop/files/group-85140-2x-1ae68523-f124-423d-82b8-8cf6c623d9a0-68c29bd9842ef.webp?width=600'],
            ['name' => 'Neeti Mohan', 'image' => 'https://estele.co/cdn/shop/files/group-85139-2x-b4cee16c-de95-48a8-bc24-c72ab9345c93-68c29bd84e263.webp?width=600'],
            ['name' => 'Yuvika Chaudhary', 'image' => 'https://estele.co/cdn/shop/files/Yuvika_Chaudhary.png?width=600'],
        ];

        foreach ($celebrities as $index => $celebrity) {
            $item = $block->items()->create([
                'title' => $celebrity['name'],
                'link_url' => route('home'),
                'sort_order' => $index,
            ]);

            try {
                $item->addMediaFromUrl($celebrity['image'])
                    ->usingFileName(\Illuminate\Support\Str::slug($celebrity['name']).'.jpg')
                    ->toMediaCollection('image');
            } catch (\Throwable $e) {
                $this->command?->warn("Could not fetch celebrity image for {$celebrity['name']}: {$e->getMessage()}");
            }
        }
    }

    private function seedUsp(): void
    {
        if (HomepageBlock::where('type', 'usp')->exists()) {
            return;
        }

        $block = HomepageBlock::create([
            'type' => 'usp',
            'title' => null,
            'sort_order' => 40,
            'is_active' => true,
        ]);

        $items = [
            ['title' => '100% Anti-Tarnish', 'body' => 'Plating that stays bright, wear after wear.'],
            ['title' => '7-Day Return & Exchange', 'body' => 'Not the right fit? Send it back, hassle-free.'],
            ['title' => 'Free Shipping Available', 'body' => 'On eligible orders, delivered to your door.'],
        ];

        foreach ($items as $index => $item) {
            $block->items()->create([
                'title' => $item['title'],
                'body' => $item['body'],
                'sort_order' => $index,
            ]);
        }
    }

    private function seedTestimonials(): void
    {
        if (HomepageBlock::where('type', 'testimonials')->exists()) {
            return;
        }

        $block = HomepageBlock::create([
            'type' => 'testimonials',
            'title' => '5M+ Happy Customers',
            'sort_order' => 50,
            'is_active' => true,
        ]);

        $items = [
            ['title' => 'Samadi', 'body' => 'Soooooo comfortable. Soooooooo beautiful. Loved every bit of it.', 'rating' => 5],
            ['title' => 'V J', 'body' => 'Absolutely love the set. The back clip secures easily and gives good support.', 'rating' => 5],
            ['title' => 'Jyotsna', 'body' => "It's beautiful, quality is good, value for money. Shines like real gold.", 'rating' => 4],
            ['title' => 'Swapnanjali', 'body' => 'Very nice and cute product, gifted it to my sister for her birthday.', 'rating' => 5],
            ['title' => 'Charu', 'body' => 'Amazing product. Very beautiful earrings, loved so much.', 'rating' => 5],
        ];

        foreach ($items as $index => $item) {
            $block->items()->create([
                'title' => $item['title'],
                'body' => $item['body'],
                'rating' => $item['rating'],
                'sort_order' => $index,
            ]);
        }
    }

    private function seedBrandStory(): void
    {
        if (HomepageBlock::where('type', 'brand_story')->exists()) {
            return;
        }

        HomepageBlock::create([
            'type' => 'brand_story',
            'title' => 'Sparkle That Stays With You',
            'subtitle' => 'Handcrafted fashion jewellery, designed to last and made to be loved.',
            'sort_order' => 60,
            'is_active' => true,
        ]);
    }

    private function seedNewsletter(): void
    {
        if (HomepageBlock::where('type', 'newsletter')->exists()) {
            return;
        }

        HomepageBlock::create([
            'type' => 'newsletter',
            'title' => 'Get the Glow — Exclusive Access Awaits',
            'subtitle' => 'Subscribe to our emailer and get 5% off your first purchase',
            'sort_order' => 70,
            'is_active' => true,
        ]);
    }
}
