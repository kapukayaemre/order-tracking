<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("categories")->insert([
            [ // 1
                "id"         => 1,
                "title"      => "Roman",
                "status"     => "active",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [ // 2
                "id"         => 2,
                "title"      => "Kişisel Gelişim",
                "status"     => "active",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [ // 3
                "id"         => 3,
                "title"      => "Bilim",
                "status"     => "active",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [ // 4
                "id"         => 4,
                "title"      => "Din Tasavvuf",
                "status"     => "active",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [ // 5
                "id"         => 5,
                "title"      => "Çocuk ve Gençlik",
                "status"     => "active",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [ // 6
                "id"         => 6,
                "title"      => "Öykü",
                "status"     => "active",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [ // 7
                "id"         => 7,
                "title"      => "Felsefe",
                "status"     => "active",
                "created_at" => now(),
                "updated_at" => now()
            ]
        ]);
    }
}
