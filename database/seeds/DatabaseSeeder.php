<?php

use App\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Call the category and user table seeds
        // $this->call([
        //     UserTableSeeder::class,
        //     CategoryTableSeeder::class,
        // ]);

        // use the Product factory with 500 record
        factory(Product::class, 50)->create();

    }
}
