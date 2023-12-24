<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //

        $this->call([
            UserSeeder::class,
            RequestSeeder::class,
            PostSeeder::class,
            CommentSeeder::class,
            InteractionSeeder::class,
        ]);
    }
}
