<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;
use App\Models\Attendance;
use App\Models\User;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        // 管理者以外のユーザーを適当に３人くらい取得
        $users = User::where('role', '!=', 'admin')->take(3)->get();
        if ($users->isEmpty()) return;

        $start = Carbon::parse('2025-07-01');
        $end = Carbon::parse('2025-08-15');
        $holidays = $this->getHolidays($start->year, $end->year);

        foreach ($users as $user) {
            $date = $start->copy();
            while ($date <= $end) {
                $w = $date->dayOfWeek;
                $dateStr = $date->toDateString();
                // 土日祝はスキップ
                if ($w === 0 || $w === 6 || isset($holidays[$dateStr])) {
                    $date->addDay();
                    continue;
                }
                // 3日に1回は残業してることにする
                $isOvertime = ($date->day % 3 === 0);
                $clockIn = $date->copy()->setTime(9, 0);
                $clockOut = $isOvertime ? $date->copy()->setTime(18, 30) : $date->copy()->setTime(18, 0);
                $overtime = $isOvertime ? 30 : 0;
                $workingTime = $clockIn->diffInMinutes($clockOut);
                Attendance::create([
                    'user_id' => $user->id,
                    'date' => $dateStr,
                    'clock_in_at' => $clockIn,
                    'clock_out_at' => $clockOut,
                    'overtime_minutes' => $overtime,
                    'working_time' => $workingTime,
                    'status_id' => 3, // 仮: 出勤
                ]);
                $date->addDay();
            }
        }
    }

    private function getHolidays($startYear, $endYear)
    {
        $holidays = [];
        for ($y = $startYear; $y <= $endYear; $y++) {
            $url = "https://holidays-jp.github.io/api/v1/{$y}/date.json";
            $json = @file_get_contents($url);
            if ($json) {
                $arr = json_decode($json, true);
                if (is_array($arr)) $holidays += $arr;
            }
        }
        return $holidays;
    }
}
