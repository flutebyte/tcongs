<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catalog = [
            'Necklace Sets' => ['Peacock', 'Halo Blossom', 'Aurora Bloom', 'Prism Petal', 'Crystal Aura'],
            'Pendant Sets' => ['Gold AD', 'Square Solitaire', 'Rhodium CZ', 'Maple Leaf', 'Emerald Drop'],
            'Earrings' => ['Pearl Drop Hoop', 'Rosegold Floral', 'Circular Stud', 'Kundan Jhumka', 'Charm Hanging'],
            'Rings' => ['Halo Crystal', 'White Crystal', 'Baguette', 'Round Stone', 'Twisted Chain'],
            'Bracelets' => ['Classic Center Stone', 'Rectangular Crystal', 'Premium Cuff', 'Single Row', 'Tennis'],
            'Bangles' => ['Resplendent Ruby', 'Daisy Flower', 'Openable Festive', 'Blossom', 'Alluring Crystal'],
            'Brooch' => ['Morbagh Ruby', 'Morbagh Green', 'Blue Green', 'White CZ', 'Feather'],
            'Choker Sets' => ['Peacock Bridal', 'Rose Motif', 'Beaded Peacock', 'Floral Rose', 'Classic Floral'],
            'Maang Tikka' => ['Fascinating CZ', 'Kundan', 'Timeless Drop', 'Lotus Pearl', 'Dazzling CZ'],
            'Mangalsutra' => ['Heavenly Crystal', 'Valley', 'Flower Double Line', 'Designer Crystal', 'Drop Flower'],
        ];

        // Demo-only placeholder photography, reused from the same Estele CDN the static
        // frontend already hotlinks — not real product photography, admin can replace via Filament.
        $images = [
            'Necklace Sets' => [
                'https://estele.co/cdn/shop/files/01_09e3acd8-c775-4737-83af-365328aec27f.jpg?v=1764929382&width=600',
                'https://estele.co/cdn/shop/files/11_86c3de45-a3ec-4713-a74b-d30d1695e4eb.jpg?v=1781712345&width=600',
                'https://estele.co/cdn/shop/files/11_b8d1a7b5-942b-4bb8-b38c-fd2e1909ad66.jpg?v=1781712299&width=600',
                'https://estele.co/cdn/shop/files/12_fe8d0f49-6289-4e61-8810-698f70d4449e.jpg?v=1781712242&width=600',
                'https://estele.co/cdn/shop/files/12_e409d360-e584-4a5f-be87-69efe598cef5.jpg?v=1781712159&width=600',
            ],
            'Pendant Sets' => [
                'https://estele.co/cdn/shop/files/6116NKER_1.jpg?v=1754737693&width=600',
                'https://estele.co/cdn/shop/products/7B3A7001_dceee9e7-bbd0-4bee-bc50-ee5ebc9ab224.jpg?v=1754900982&width=600',
                'https://estele.co/cdn/shop/products/AD-002-NKER.jpg?v=1754476745&width=600',
                'https://estele.co/cdn/shop/products/DSC_4090_de371b54-9a84-4b01-a8b4-64b99c3438d1.jpg?v=1738652598&width=600',
                'https://estele.co/cdn/shop/products/9330-NKER-1.jpg?v=1676528798&width=600',
            ],
            'Earrings' => [
                'https://estele.co/cdn/shop/products/61Hs13XyXqL._UL1500.jpg?v=1661339317&width=600',
                'https://estele.co/cdn/shop/files/10239-RGWEERER.jpg?v=1739861540&width=600',
                'https://estele.co/cdn/shop/files/15_5a52dda9-46c3-4bb9-952a-3dd8502cb6a5.jpg?v=1754483514&width=600',
                'https://estele.co/cdn/shop/products/7B3A9891.jpg?v=1675932132&width=600',
                'https://estele.co/cdn/shop/files/01_74423695-9954-4084-8040-2932220718ca.jpg?v=1754474630&width=600',
            ],
            'Rings' => [
                'https://estele.co/cdn/shop/files/Untitled-6_751ef63a-d853-40a5-b2ff-e3a758807241.jpg?v=1776249142&width=600',
                'https://estele.co/cdn/shop/files/Untitled-6_930d65be-d9f4-44d9-828f-7a513a45d6b9.jpg?v=1776248283&width=600',
                'https://estele.co/cdn/shop/files/Untitled-5_b4e9dcf9-44c3-4522-a31d-7812892e7c16.jpg?v=1776247867&width=600',
                'https://estele.co/cdn/shop/files/Untitled-4_081ba177-ea8b-4991-98ab-6403b6ba82cb.jpg?v=1775131093&width=600',
                'https://estele.co/cdn/shop/files/Untitled-6_94e1b4df-43eb-43c0-9ebb-5d0858970d3d.jpg?v=1776074347&width=600',
            ],
            'Bracelets' => [
                'https://estele.co/cdn/shop/files/Untitled-2_f76eb1fe-1fa9-4095-a8e3-d2bfc2a2d08f.jpg?v=1781171856&width=600',
                'https://estele.co/cdn/shop/files/Untitled-2_72f77697-6af0-47a1-9e46-66a45cd26a09.jpg?v=1781171740&width=600',
                'https://estele.co/cdn/shop/files/Untitled-2_9267725e-be3a-4fbd-a8f0-561e055fa775.jpg?v=1781172177&width=600',
                'https://estele.co/cdn/shop/files/10_e41e6906-8e1d-47c8-bd52-ccecfa47e8d8.jpg?v=1780046470&width=600',
                'https://estele.co/cdn/shop/files/7_05fb1a1c-c58b-4db9-af34-232215f5df2c.jpg?v=1780046350&width=600',
            ],
            'Bangles' => [
                'https://estele.co/cdn/shop/files/582A9037copy_33dce07f-0a67-4fc8-b644-9011ce2c0e1b.jpg?v=1752560819&width=600',
                'https://estele.co/cdn/shop/files/AD-006-IRBANGLE_f2477763-3b9a-455d-8256-1f93b2850c16.jpg?v=1718899206&width=600',
                'https://estele.co/cdn/shop/files/Untitled-1_1271960b-df21-4d66-aee8-09a7e7922a50.jpg?v=1770388461&width=600',
                'https://estele.co/cdn/shop/files/582A9038copy_14a4bfe5-d016-4383-a5b3-518b0f9bde50.jpg?v=1752561058&width=600',
                'https://estele.co/cdn/shop/products/5F7A0934.jpg?v=1656005588&width=600',
            ],
            'Brooch' => [
                'https://estele.co/cdn/shop/files/Untitled-1_7489766a-87ff-4a62-8aec-3e726e640d9a.jpg?v=1776504147&width=600',
                'https://estele.co/cdn/shop/files/Untitled-1_476511c3-fcbb-4b59-b07a-bd23998fecdd.jpg?v=1776504265&width=600',
                'https://estele.co/cdn/shop/files/Untitled-1_d8a547c0-fda4-499c-a096-74e9172675f2.jpg?v=1776504376&width=600',
                'https://estele.co/cdn/shop/files/Untitled-1_1f66ca5f-9213-4861-a4e2-01009b574178.jpg?v=1776504499&width=600',
                'https://estele.co/cdn/shop/files/Untitled-1_6393b806-92d2-452d-9564-e6e180934576.jpg?v=1776504663&width=600',
            ],
            'Choker Sets' => [
                'https://estele.co/cdn/shop/products/AD-545-N_E_1.jpg?v=1645873364&width=600',
                'https://estele.co/cdn/shop/files/1_f23360d3-aefb-4a75-8efd-3261450df84c.jpg?v=1742305117&width=600',
                'https://estele.co/cdn/shop/files/Untitled-1_33ed2e2f-7ecd-4454-9a03-cf06faf863ee.jpg?v=1770443289&width=600',
                'https://estele.co/cdn/shop/files/1_c32d17f5-399b-4f3f-af00-79d43c392be4.jpg?v=1742304818&width=600',
                'https://estele.co/cdn/shop/files/1_88986eee-8dda-4247-92fe-de43dde80987.jpg?v=1742305009&width=600',
            ],
            'Maang Tikka' => [
                'https://estele.co/cdn/shop/products/AD-MT-020-RGA_TIKAA_1.jpg?v=1696354345&width=600',
                'https://estele.co/cdn/shop/files/030-IGTIKKA.jpg?v=1761040395&width=600',
                'https://estele.co/cdn/shop/files/Untitled-1_9eee3a42-d198-4db9-914a-044b3ed266a9.jpg?v=1780565856&width=600',
                'https://estele.co/cdn/shop/files/003_a4d5899d-9e2f-43cd-97bf-d02fa968b790.jpg?v=1771998371&width=600',
                'https://estele.co/cdn/shop/files/AD-MT-024-IRWETIKAA.jpg?v=1766137881&width=600',
            ],
            'Mangalsutra' => [
                'https://estele.co/cdn/shop/files/1_d20f4a0a-6b18-4b90-aedc-6a816c6a3523.jpg?v=1752570215&width=600',
                'https://estele.co/cdn/shop/products/IMG_9493.jpg?v=1756204665&width=600',
                'https://estele.co/cdn/shop/products/7_83a85db0-d78f-4977-a95b-5e78e2cde658.jpg?v=1649445060&width=600',
                'https://estele.co/cdn/shop/files/Untitled-2_6fbbb758-7d8c-4db2-971c-0cafbf7f7368.jpg?v=1774936742&width=600',
                'https://estele.co/cdn/shop/files/1_8e0218fa-b0fa-4137-a573-845b07744cf1.jpg?v=1763624728&width=600',
            ],
        ];

        $sku = 1000;

        foreach ($catalog as $categoryName => $variants) {
            $category = Category::where('slug', Str::slug($categoryName))->first();

            if (! $category) {
                continue;
            }

            foreach ($variants as $index => $variant) {
                $title = "{$variant} {$categoryName}";
                $price = random_int(4, 40) * 100 + 99;
                $compareAt = (bool) random_int(0, 1) ? $price + random_int(200, 2000) : null;
                $sku++;

                $product = Product::updateOrCreate(
                    ['slug' => Str::slug($title)],
                    [
                        'title' => $title,
                        'sku' => 'EST-'.$sku,
                        'description' => "A handcrafted {$variant} design from our {$categoryName} collection, finished with anti-tarnish plating.",
                        'price' => $price,
                        'compare_at_price' => $compareAt,
                        'stock_quantity' => random_int(0, 50),
                        'is_active' => true,
                        'is_featured' => $index === 0,
                    ]
                );

                $product->categories()->syncWithoutDetaching([$category->id]);

                if (! $product->hasMedia('gallery') && isset($images[$categoryName][$index])) {
                    try {
                        $product->addMediaFromUrl($images[$categoryName][$index])
                            ->usingFileName(Str::slug($title).'.jpg')
                            ->toMediaCollection('gallery');
                    } catch (\Throwable $e) {
                        $this->command?->warn("Could not fetch demo image for {$title}: {$e->getMessage()}");
                    }
                }
            }
        }
    }
}
