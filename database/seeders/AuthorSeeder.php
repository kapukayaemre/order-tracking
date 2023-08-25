<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("authors")->insert([
            [ // 1
                "id"         => 1,
                "name"       => "Yaşar Kemal",
                "status"     => "active",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [ // 2
                "id"         => 2,
                "name"       => "Oğuz Atay",
                "status"     => "active",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [ // 3
                "id"         => 3,
                "name"       => "Sabahattin Ali",
                "status"     => "active",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [ // 4
                "id"         => 4,
                "name"       => "John Steinback",
                "status"     => "active",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [ // 5
                "id"         => 5,
                "name"       => "Jose Mauro De Vasconcelos",
                "status"     => "active",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [ // 6
                "id"         => 6,
                "name"       => "Hakan Mengüç",
                "status"     => "active",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [ // 7
                "id"         => 7,
                "name"       => "Stephen Hawking",
                "status"     => "active",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [ // 8
                "id"         => 8,
                "name"       => "Uğur Koşar",
                "status"     => "active",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [ // 9
                "id"         => 9,
                "name"       => "Mehmet Yıldız",
                "status"     => "active",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [ // 10
                "id"         => 10,
                "name"       => "Mert Arık",
                "status"     => "active",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [ // 11
                "id"         => 11,
                "name"       => "Marcus Aurelius",
                "status"     => "active",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [ // 12
                "id"         => 12,
                "name"       => "Michel de Montaigne",
                "status"     => "active",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [ // 13
                "id"         => 13,
                "name"       => "George Orwell",
                "status"     => "active",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [ // 14
                "id"         => 14,
                "name"       => "Peyami Safa",
                "status"     => "active",
                "created_at" => now(),
                "updated_at" => now()
            ]
        ]);
    }
}
