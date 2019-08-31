<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Array of the categories to be seed 
        $categories = [
            ['name' => 'Home'],
            ['name' => 'Electronics'],
            ['name' => 'Helath & beauty'],
            ['name' => 'Grocery'],
            ['name' => 'Sport'],
            ['name' => 'Phones & Tablets'],
            ['name' => 'Fashion'],
            ['name' => 'Computing'],
        ];

        // Insert categories array in categories table 
        DB::table('categories')->insert($categories);
    }
}
