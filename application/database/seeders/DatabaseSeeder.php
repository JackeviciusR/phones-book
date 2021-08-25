<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Share;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'username';
        $user->email = 'username@gmail.com';
        $user->email_verified_at = now();
        $user->password = Hash::make('slaptazodis');
        $user->remember_token = Str::random(10);
        $user->created_at = now();
        $user->created_at = now();
        $user->save();

        User::factory(5)->create();
        Contact::factory(30)->create();
        Share::factory(10)->create();
    }

}
