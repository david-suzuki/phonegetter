<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        // User::create(['email'=>'admin@mail.ru', 'password'=>bcrypt('asdfghjkl')]);
        // User::create(['email'=>'alexandr@mail.ru', 'password'=>bcrypt('asdfghjkl')]);
        User::create(['uname'=>'Kop', 'account'=>'admin123', 'password'=>bcrypt('asdfghjkl'), 'role'=>'admin', 'status'=>'active']);
        User::create(['uname'=>'Alex', 'account'=>'user1233456', 'password'=>bcrypt('asdfghjkl'), 'role'=>'user', 'status'=>'inactive']);
    }
}
