<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
{
    $superId = DB::table("users")->insertGetId([
        "name" => "super",
        "email" => "super@vmi.lan",
        "password" => bcrypt("Aa123?"),
        "role" => "superadmin"
    ]);

    DB::table("user_profiles")->insert([
        "user_id" => $superId,
        "full_name" => "Super Admin",
        "city" => "Budapest",
        "address" => "Super utca 1",
        "phone" => "0612345678"
    ]);


    $adminId = DB::table("users")->insertGetId([
        "name" => "admin",
        "email" => "admin@vmi.lan",
        "password" => bcrypt("Aa123?"),
        "role" => "admin"
    ]);

    DB::table("user_profiles")->insert([
        "user_id" => $adminId,
        "full_name" => "Admin User",
        "city" => "Debrecen",
        "address" => "Admin utca 2",
        "phone" => "0620123456"
    ]);


    $userId = DB::table("users")->insertGetId([
        "name" => "user",
        "email" => "user@vmi.lan",
        "password" => bcrypt("Aa123?"),
        "role" => "user"
    ]);

    DB::table("user_profiles")->insert([
        "user_id" => $userId,
        "full_name" => "Normal User",
        "city" => "Szeged",
        "address" => "User utca 3",
        "phone" => "0630123456"
    ]);
}
}
