<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('departments')->insert([
            [
                'code' => '01',
                'name' => '総務部',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => '02',
                'name' => '営業部',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => '03',
                'name' => '技術部',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => '04',
                'name' => '経理部',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
