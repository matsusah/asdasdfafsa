<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = bcrypt("admin");
        $users = bcrypt("pengguna");
        DB::insert("INSERT INTO `users` (`name`,`email`,`password`, `level_users`, `is_active`,`token`) VALUES
            ('admin','admin@gmail.com','$admin', 'Admin', 1,'6115DKStDMpNGKq92dad524ee8b6b37'),
            ('pengguna','pengguna@gmail.com', '$users', 'Pengguna', 1,'6115DKStDMpNGKq92dad524ee8b2121')
        ");
    }
}
