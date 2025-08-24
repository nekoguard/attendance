<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('statuses')->insert([
            [
                'field' => 'clock_in_at',
                'name' => '在席',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'field' => 'break_start_at',
                'name' => '外出中',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'field' => 'clock_out_at',
                'name' => '退勤',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'field' => 'break_end_at',
                'name' => '在席',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'field' => 'telework',
                'name' => '在宅勤務',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'field' => 'holiday',
                'name' => '休み',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
