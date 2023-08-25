<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::factory()->create([
            'name'              => 'Admin',
            'surname'           => 'Admino',
            'email'             => 'admin@admin.com',
            'email_verified_at' => now(),
            'password'          => bcrypt("password"),
            'remember_token'    => Str::random(10),
            'status'            => "active"

        ]);

        $this->call([
            CategorySeeder::class,
            AuthorSeeder::class,
            ProductSeeder::class
        ]);
    }
}
