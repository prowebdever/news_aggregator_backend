<?php

namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with initial sources.
     *
     * @return void
     */
    public function run()
    {
        // Add the initial sources to the database
        Source::insert([
            ['id' => 1, 'name' => 'News API'],
            ['id' => 2, 'name' => 'The Guardian'],
            ['id' => 3, 'name' => 'New York Times'],
        ]);
    }
}
