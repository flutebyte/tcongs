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

        // Second-angle photos for the hover-swap effect on product cards, matching
        // the original site's real "primary photo + hover photo" pattern. Demo-only
        // placeholder photography, same as $images above — not per-item SKU matches.
        $hoverImages = [
            'Necklace Sets' => [
                'https://estele.co/cdn/shop/files/13_ff4a151c-976a-48c6-9e55-6df68f7c3c9c.jpg?width=600',
                'https://estele.co/cdn/shop/files/13_f6328779-8255-456f-90d7-07e5a64b3ba0.jpg?width=600',
                'https://estele.co/cdn/shop/files/13_912f323c-3171-465b-9267-f5cdb70d3d63.jpg?width=600',
                'https://estele.co/cdn/shop/files/12_14685aa0-aa94-4969-9eb4-098f35432f38.jpg?width=600',
                'https://estele.co/cdn/shop/files/12_1981ecd6-8377-42c3-92a8-d41f5ffafec8.jpg?width=600',
            ],
            'Pendant Sets' => [
                'https://estele.co/cdn/shop/files/1_cb624c56-6fe9-4fe7-99d7-32f8658733bf.jpg?width=600',
                'https://estele.co/cdn/shop/files/Untitled-2_06738b7b-014b-421c-8946-ced27be0fa2c.jpg?width=600',
                'https://estele.co/cdn/shop/files/Untitled-2_0fdba062-f642-4b50-b167-91d46144c496.jpg?width=600',
                'https://estele.co/cdn/shop/files/Untitled-2_5f2fcc7f-a160-4e35-9f13-911fcc26fd56.jpg?width=600',
                'https://estele.co/cdn/shop/files/Untitled-2_40b0d76f-357e-42ea-a2fa-5b2cbfc2b5d2.jpg?width=600',
            ],
            'Earrings' => [
                'https://estele.co/cdn/shop/files/Untitled-2_e7a4805d-a903-46d1-98b1-d7819a76cd17.jpg?width=600',
                'https://estele.co/cdn/shop/files/1_57df753f-7bed-43c7-ae8b-711c6d9ce94b.jpg?width=600',
                'https://estele.co/cdn/shop/files/Untitled-2_d75a8973-061b-4831-820b-18228942b38a.jpg?width=600',
                'https://estele.co/cdn/shop/files/Untitled-2_3f1e4c1a-671e-4d47-b706-f8925c149c86.jpg?width=600',
                'https://estele.co/cdn/shop/files/Untitled-2_e0b5a5da-c4f6-431c-9501-d8f2914601a8.jpg?width=600',
            ],
            'Rings' => [
                'https://estele.co/cdn/shop/files/Untitled-2_e5b5b245-1de5-4251-ae01-437e7d56cb3f.jpg?width=600',
                'https://estele.co/cdn/shop/files/Untitled-2_8ecf423c-e50c-4216-b1b8-a7ddbb0df6a1.jpg?width=600',
                'https://estele.co/cdn/shop/files/Untitled-2_e563e9f4-2c5d-48d6-b253-cee641886bcb.jpg?width=600',
                'https://estele.co/cdn/shop/files/Untitled-2_8038c8a5-0d82-40b7-af9d-2d3a68db8499.jpg?width=600',
                'https://estele.co/cdn/shop/files/5_4c6f7d71-39c4-4090-8c7f-ff83fae7a5c1.jpg?width=600',
            ],
            'Bracelets' => [
                'https://estele.co/cdn/shop/files/5_d7756800-a101-48de-a6e1-d7ac191bbbf8.jpg?width=600',
                'https://estele.co/cdn/shop/files/Untitled-2_07d11b2b-0da9-4cef-909e-8df3bdba6276.jpg?width=600',
                'https://estele.co/cdn/shop/files/Untitled-3_45df7cc5-8911-401d-b3aa-d8a4a98a0629.jpg?width=600',
                'https://estele.co/cdn/shop/files/Untitled-2_223219d3-e148-4920-b457-1b69d7dba666.jpg?width=600',
                'https://estele.co/cdn/shop/files/Untitled-2_d9102494-e435-4dcc-bf7d-895f116b4efb.jpg?width=600',
            ],
            'Bangles' => [
                'https://estele.co/cdn/shop/files/5_608ce7b8-f55a-426f-9df4-4bd7a284df22.jpg?width=600',
                'https://estele.co/cdn/shop/files/6_93abb9c1-9753-4c43-b7bf-51eaa7c3534f.jpg?width=600',
                'https://estele.co/cdn/shop/files/Untitled-2_4d16f477-a347-4d9f-bc28-c45608cfe9c4.jpg?width=600',
                'https://estele.co/cdn/shop/files/Untitled-2_f48e74a2-5476-4864-a54e-da84df75c528.jpg?width=600',
                'https://estele.co/cdn/shop/files/Untitled-2_1a78b711-8d35-48fb-9478-e9898a165e31.jpg?width=600',
            ],
            'Brooch' => [
                'https://estele.co/cdn/shop/files/02_b28f8d5e-85b0-4cef-8483-90342deafcd0.jpg?width=600',
                'https://estele.co/cdn/shop/files/6116NKER_2.jpg?width=600',
                'https://estele.co/cdn/shop/files/1_fb8bd35f-7ce0-47d0-986c-ee95576dc882.jpg?width=600',
                'https://estele.co/cdn/shop/files/IMG_9483_7f00b166-ed58-4c60-af54-425d6d50c064.jpg?width=600',
                'https://estele.co/cdn/shop/files/156PENDANT_1.jpg?width=600',
            ],
            'Choker Sets' => [
                'https://estele.co/cdn/shop/files/9782-RGNKER_3.jpg?width=600',
                'https://estele.co/cdn/shop/files/699-701-IG-ER.jpg?width=600',
                'https://estele.co/cdn/shop/products/7_d163c1c6-305d-4a9b-800b-b180e58d31e0.jpg?width=600',
                'https://estele.co/cdn/shop/products/DSC_4090_e400175a-c832-4212-8583-8b3d6e02c0a2.jpg?width=600',
                'https://estele.co/cdn/shop/files/9085NKER.jpg?width=600',
            ],
            'Maang Tikka' => [
                'https://estele.co/cdn/shop/products/9492_NKER_2.jpg?width=600',
                'https://estele.co/cdn/shop/products/AD-716-RG-RB_NKER_1.jpg?width=600',
                'https://estele.co/cdn/shop/files/003_9cbc4f7c-12c1-41b0-a59d-07f70ede59a4.jpg?width=600',
                'https://estele.co/cdn/shop/files/12_664c7af6-eb1d-44fc-9410-bcf3c253a805.jpg?width=600',
                'https://estele.co/cdn/shop/files/13_ff4a151c-976a-48c6-9e55-6df68f7c3c9c.jpg?width=600',
            ],
            'Mangalsutra' => [
                'https://estele.co/cdn/shop/files/13_f6328779-8255-456f-90d7-07e5a64b3ba0.jpg?width=600',
                'https://estele.co/cdn/shop/files/13_912f323c-3171-465b-9267-f5cdb70d3d63.jpg?width=600',
                'https://estele.co/cdn/shop/files/12_14685aa0-aa94-4969-9eb4-098f35432f38.jpg?width=600',
                'https://estele.co/cdn/shop/files/12_1981ecd6-8377-42c3-92a8-d41f5ffafec8.jpg?width=600',
                'https://estele.co/cdn/shop/files/1_cb624c56-6fe9-4fe7-99d7-32f8658733bf.jpg?width=600',
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

                if ($product->getMedia('gallery')->count() < 2 && isset($hoverImages[$categoryName][$index])) {
                    try {
                        $product->addMediaFromUrl($hoverImages[$categoryName][$index])
                            ->usingFileName(Str::slug($title).'-hover.jpg')
                            ->toMediaCollection('gallery');
                    } catch (\Throwable $e) {
                        $this->command?->warn("Could not fetch hover demo image for {$title}: {$e->getMessage()}");
                    }
                }
            }
        }
    }
}
