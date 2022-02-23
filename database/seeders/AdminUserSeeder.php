<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->updateOrCreate(['email' => 'admin@bms.com'], [
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'is_admin' => 1,
            'phone' => '9867342105',
            'password' => 'admin'
        ]);
    }
}
