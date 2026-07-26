<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\CmsPage;
use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            ['title' => 'About Us', 'slug' => 'about-us', 'content' => '<p>Welcome to our story — a jewellery brand built on craftsmanship and care.</p>'],
            ['title' => 'Privacy Policy', 'slug' => 'privacy-policy', 'content' => '<p>We respect your privacy. This policy explains what data we collect and how we use it.</p>'],
            ['title' => 'Shipping Policy', 'slug' => 'shipping-policy', 'content' => '<p>Orders are processed within 1-2 business days and shipped via our courier partners.</p>'],
            ['title' => 'Return Policy', 'slug' => 'return-policy', 'content' => '<p>Not happy with your order? Returns are accepted within 7 days of delivery.</p>'],
            ['title' => 'Franchise', 'slug' => 'franchise', 'content' => '<p>Interested in partnering with us? Reach out to our franchise team.</p>'],
        ];

        foreach ($pages as $page) {
            CmsPage::firstOrCreate(
                ['slug' => $page['slug']],
                ['title' => $page['title'], 'content' => $page['content'], 'status' => 'published']
            );
        }

        $styleGuide = BlogCategory::firstOrCreate(['slug' => 'style-guide'], ['name' => 'Style Guide', 'sort_order' => 1]);
        $careTips = BlogCategory::firstOrCreate(['slug' => 'care-tips'], ['name' => 'Care Tips', 'sort_order' => 2]);

        Blog::firstOrCreate(
            ['slug' => 'how-to-choose-your-first-necklace'],
            [
                'blog_category_id' => $styleGuide->id,
                'title' => 'How to Choose Your First Necklace',
                'excerpt' => 'A quick guide to picking a necklace that suits your style and neckline.',
                'content' => '<p>Choosing your first necklace can feel overwhelming — here are a few tips to get started.</p>',
                'author_name' => 'Team Estele',
                'status' => 'published',
                'published_at' => now()->subDays(5),
            ]
        );

        Blog::firstOrCreate(
            ['slug' => 'caring-for-anti-tarnish-jewellery'],
            [
                'blog_category_id' => $careTips->id,
                'title' => 'Caring for Anti-Tarnish Jewellery',
                'excerpt' => 'Simple habits to keep your jewellery looking new for longer.',
                'content' => '<p>Anti-tarnish jewellery still benefits from a little care — here is how.</p>',
                'author_name' => 'Team Estele',
                'status' => 'published',
                'published_at' => now()->subDays(2),
            ]
        );

        $ordering = FaqCategory::firstOrCreate(['name' => 'Ordering'], ['sort_order' => 1]);
        $shipping = FaqCategory::firstOrCreate(['name' => 'Shipping'], ['sort_order' => 2]);

        Faq::firstOrCreate(
            ['question' => 'How do I place an order?'],
            ['faq_category_id' => $ordering->id, 'answer' => 'Simply add a product to your cart and proceed to checkout.', 'sort_order' => 1]
        );
        Faq::firstOrCreate(
            ['question' => 'Can I modify my order after placing it?'],
            ['faq_category_id' => $ordering->id, 'answer' => 'Contact our support team as soon as possible and we will do our best to help.', 'sort_order' => 2]
        );
        Faq::firstOrCreate(
            ['question' => 'How long does delivery take?'],
            ['faq_category_id' => $shipping->id, 'answer' => 'Orders typically arrive within 3-7 business days depending on your location.', 'sort_order' => 1]
        );
    }
}
