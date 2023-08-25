<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("products")->insert([
            [ // 1
                "id"             => 1,
                "category_id"    => 1,
                "author_id"      => 1,
                "title"          => "İnce Memed",
                "description"    => null,
                "price"          => 48.75,
                "stock_quantity" => 10,
                "status"         => "active",
                "created_at"     => now(),
                "updated_at"     => now()
            ],
            [ // 2
                "id"             => 2,
                "category_id"    => 1,
                "author_id"      => 2,
                "title"          => "Tutunamayanlar",
                "description"    => null,
                "price"          => 90.30,
                "stock_quantity" => 20,
                "status"         => "active",
                "created_at"     => now(),
                "updated_at"     => now()
            ],
            [ // 3
                "id"             => 3,
                "category_id"    => 1,
                "author_id"      => 3,
                "title"          => "Kürk Mantolu Madonna",
                "description"    => null,
                "price"          => 9.10,
                "stock_quantity" => 4,
                "status"         => "active",
                "created_at"     => now(),
                "updated_at"     => now()
            ],
            [ // 4
                "id"             => 4,
                "category_id"    => 1,
                "author_id"      => 4,
                "title"          => "Fareler ve İnsanlar",
                "description"    => null,
                "price"          => 35.75,
                "stock_quantity" => 8,
                "status"         => "active",
                "created_at"     => now(),
                "updated_at"     => now()
            ],
            [ // 5
                "id"             => 5,
                "category_id"    => 1,
                "author_id"      => 5,
                "title"          => "Şeker Portakalı",
                "description"    => null,
                "price"          => 33.00,
                "stock_quantity" => 1,
                "status"         => "active",
                "created_at"     => now(),
                "updated_at"     => now()
            ],
            [ // 6
                "id"             => 6,
                "category_id"    => 2,
                "author_id"      => 6,
                "title"          => "Sen Yola Çık Yol Sana Görünür",
                "description"    => null,
                "price"          => 28.50,
                "stock_quantity" => 7,
                "status"         => "active",
                "created_at"     => now(),
                "updated_at"     => now()
            ],
            [ // 7
                "id"             => 7,
                "category_id"    => 3,
                "author_id"      => 7,
                "title"          => "Kara Delikler",
                "description"    => null,
                "price"          => 39.00,
                "stock_quantity" => 2,
                "status"         => "active",
                "created_at"     => now(),
                "updated_at"     => now()
            ],
            [ // 8
                "id"             => 8,
                "category_id"    => 4,
                "author_id"      => 8,
                "title"          => "Allah De Ötesini Bırak",
                "description"    => null,
                "price"          => 39.60,
                "stock_quantity" => 18,
                "status"         => "active",
                "created_at"     => now(),
                "updated_at"     => now()
            ],
            [ // 9
                "id"             => 9,
                "category_id"    => 4,
                "author_id"      => 9,
                "title"          => "Aşk 5 Vakittir",
                "description"    => null,
                "price"          => 42.00,
                "stock_quantity" => 9,
                "status"         => "active",
                "created_at"     => now(),
                "updated_at"     => now()
            ],
            [ // 10
                "id"             => 10,
                "category_id"    => 5,
                "author_id"      => 10,
                "title"          => "Benim Zürafam Uçabilir",
                "description"    => null,
                "price"          => 27.30,
                "stock_quantity" => 12,
                "status"         => "active",
                "created_at"     => now(),
                "updated_at"     => now()
            ],
            [ // 11
                "id"             => 11,
                "category_id"    => 1,
                "author_id"      => 3,
                "title"          => "Kuyucaklı Yusuf",
                "description"    => null,
                "price"          => 10.40,
                "stock_quantity" => 2,
                "status"         => "active",
                "created_at"     => now(),
                "updated_at"     => now()
            ],
            [ // 12
                "id"             => 12,
                "category_id"    => 6,
                "author_id"      => 3,
                "title"          => "Kamyon - Seçme Öyküler",
                "description"    => null,
                "price"          => 9.75,
                "stock_quantity" => 9,
                "status"         => "active",
                "created_at"     => now(),
                "updated_at"     => now()
            ],
            [ // 13
                "id"             => 13,
                "category_id"    => 7,
                "author_id"      => 11,
                "title"          => "Kendime Düşünceler",
                "description"    => null,
                "price"          => 14.40,
                "stock_quantity" => 1,
                "status"         => "active",
                "created_at"     => now(),
                "updated_at"     => now()
            ],
            [ // 14
                "id"             => 14,
                "category_id"    => 7,
                "author_id"      => 12,
                "title"          => "Denemeler - Hasan Ali Yücel Klasikleri",
                "description"    => null,
                "price"          => 24.00,
                "stock_quantity" => 4,
                "status"         => "active",
                "created_at"     => now(),
                "updated_at"     => now()
            ],
            [ // 15
                "id"             => 15,
                "category_id"    => 1,
                "author_id"      => 13,
                "title"          => "Animal Farm",
                "description"    => null,
                "price"          => 17.50,
                "stock_quantity" => 1,
                "status"         => "active",
                "created_at"     => now(),
                "updated_at"     => now()
            ],
            [ // 16
                "id"             => 16,
                "category_id"    => 1,
                "author_id"      => 14,
                "title"          => "Dokuzuncu Hariciye Koğuşu",
                "description"    => null,
                "price"          => 18.50,
                "stock_quantity" => 0,
                "status"         => "passive",
                "created_at"     => now(),
                "updated_at"     => now()
            ],

        ]);
    }
}
