<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // マスターデータ取得
        $departments = \DB::table('departments')->get();
        $sections = \DB::table('sections')->get();
        $positions = \DB::table('positions')->get();
        $workTypes = \DB::table('work_types')->get();
        $statuses = \DB::table('statuses')->get();

        // 管理者権限ユーザーを追加
        $adminEmpNo = '9900001';
        $adminUserId = \DB::table('users')->insertGetId([
            'name' => $adminEmpNo,
            'email' => 'admin@example.com',
            'password' => bcrypt('SkwD9TSF'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // user_infosテーブルに管理者情報を追加
        \DB::table('user_infos')->insert([
            'user_id' => $adminUserId,
            'last_name' => '管理',
            'first_name' => '者',
            'department_id' => $departments->first()->id,
            'section_id' => $sections->first()->id,
            'position_id' => $positions->where('name', '管理者')->first()?->id ?? $positions->first()->id, // 管理者がいない場合は最初の役職を使用
            'work_type_id' => $workTypes->first()->id,
            'status_id' => $statuses->first()->id,
            'paid_leave_remaining' => 10.0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 各部署・課に2人ずつユーザーを追加
        $employeeNumber = 1; // 社員番号の初期値
        foreach ($departments as $dep) {
            // 各部署の課を取得
            $depSections = $sections->where('department_id', $dep->id);
            // 各課に2人ずつユーザーを追加
            foreach ($depSections as $sec) {
                for ($i = 0; $i < 2; $i++) {
                    // 社員番号を生成（0埋め3桁にする）
                    $empNo = $dep->code . $sec->code . str_pad($employeeNumber, 3, '0', STR_PAD_LEFT);
                    // usersテーブルに追加（パスワードは社員番号2回繰り返しにする）
                    $userId = \DB::table('users')->insertGetId([
                        'name' => $empNo, // 社員番号
                        'email' => 'user' . $empNo . '@example.com',
                        'password' => bcrypt($empNo . $empNo),
                        'role' => 'member',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // user_infosテーブルに追加
                    \DB::table('user_infos')->insert([
                        'user_id' => $userId,
                        'last_name' => '姓' . $employeeNumber,
                        'first_name' => '名' . $employeeNumber,
                        'department_id' => $dep->id,
                        'section_id' => $sec->id,
                        'position_id' => $positions->where('name', ['一般', '主任', '課長', '部長'][$i % 4])->first()->id,
                        'work_type_id' => $workTypes->get($i % $workTypes->count())->id,
                        'status_id' => $statuses->get($i % $statuses->count())->id,
                        'paid_leave_remaining' => 10.0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $employeeNumber++;
                }
            }
        }
    }
}
