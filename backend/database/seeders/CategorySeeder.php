<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Same demo-only Estele CDN source the static frontend already hotlinks and
        // ProductSeeder already pulls from — one representative image per category.
        $categories = [
            ['name' => 'Necklace Sets', 'description' => 'Statement necklace sets for every occasion.', 'image' => 'https://estele.co/cdn/shop/files/01_09e3acd8-c775-4737-83af-365328aec27f.jpg?v=1764929382&width=600'],
            ['name' => 'Pendant Sets', 'description' => 'Delicate pendant sets for everyday wear.', 'image' => 'https://estele.co/cdn/shop/files/6116NKER_1.jpg?v=1754737693&width=600'],
            ['name' => 'Earrings', 'description' => 'Studs, hoops and jhumkas.', 'image' => 'https://estele.co/cdn/shop/products/61Hs13XyXqL._UL1500.jpg?v=1661339317&width=600'],
            ['name' => 'Rings', 'description' => 'Adjustable and statement rings.', 'image' => 'https://estele.co/cdn/shop/files/Untitled-6_751ef63a-d853-40a5-b2ff-e3a758807241.jpg?v=1776249142&width=600'],
            ['name' => 'Bracelets', 'description' => 'Tennis, cuff and chain bracelets.', 'image' => 'https://estele.co/cdn/shop/files/Untitled-2_f76eb1fe-1fa9-4095-a8e3-d2bfc2a2d08f.jpg?v=1781171856&width=600'],
            ['name' => 'Bangles', 'description' => 'Traditional and contemporary bangles.', 'image' => 'https://estele.co/cdn/shop/files/582A9037copy_33dce07f-0a67-4fc8-b644-9011ce2c0e1b.jpg?v=1752560819&width=600'],
            ['name' => 'Brooch', 'description' => 'Brooch pins for sarees and blazers.', 'image' => 'https://estele.co/cdn/shop/files/Untitled-1_7489766a-87ff-4a62-8aec-3e726e640d9a.jpg?v=1776504147&width=600'],
            ['name' => 'Choker Sets', 'description' => 'Bridal and party choker sets.', 'image' => 'https://estele.co/cdn/shop/products/AD-545-N_E_1.jpg?v=1645873364&width=600'],
            ['name' => 'Maang Tikka', 'description' => 'Maang tikka for festive and bridal looks.', 'image' => 'https://estele.co/cdn/shop/products/AD-MT-020-RGA_TIKAA_1.jpg?v=1696354345&width=600'],
            ['name' => 'Mangalsutra', 'description' => 'Traditional and modern mangalsutra designs.', 'image' => 'https://estele.co/cdn/shop/files/1_d20f4a0a-6b18-4b90-aedc-6a816c6a3523.jpg?v=1752570215&width=600'],
        ];

        foreach ($categories as $index => $category) {
            $model = Category::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'sort_order' => $index,
                ]
            );

            if (! $model->hasMedia('image')) {
                try {
                    $model->addMediaFromUrl($category['image'])
                        ->usingFileName($model->slug.'.jpg')
                        ->toMediaCollection('image');
                } catch (\Throwable $e) {
                    $this->command?->warn("Could not fetch demo image for {$category['name']}: {$e->getMessage()}");
                }
            }
        }
    }
}
