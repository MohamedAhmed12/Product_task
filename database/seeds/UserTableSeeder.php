<?php

use App\User;

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // use( instantiate) User model in oreder to have access to is_admin coulmn as it's not in fillable
        $user = new User;
        $user->name = "Admin";
        $user->email = "mohamed7el17@gmail.com";
        $user->password = bcrypt('123');
        $user->is_admin = true;
        $user->save();

    }
}
