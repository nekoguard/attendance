<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('positions')->insert([
            [
                'name' => '一般',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => '主任',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => '課長',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => '部長',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => '管理者',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
