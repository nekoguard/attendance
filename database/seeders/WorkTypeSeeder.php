<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('work_types')->insert([
            [
                'name' => '通常',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => '時短',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'フレックス',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
