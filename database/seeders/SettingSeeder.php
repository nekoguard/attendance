<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'required_work_days',
                'value' => '20',
                'type' => 'integer',
            ],
            [
                'key' => 'default_clock_in_time',
                'value' => '09:00',
                'type' => 'string',
            ],
            [
                'key' => 'default_clock_out_time',
                'value' => '18:00',
                'type' => 'string',
            ],
            [
                'key' => 'default_break_time',
                'value' => '01:00', // 1時間
                'type' => 'string',
            ],
        ];
        foreach ($settings as $s) {
            Setting::updateOrCreate(['key' => $s['key']], $s);
        }
    }
}
