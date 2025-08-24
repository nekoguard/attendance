<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = \DB::table('departments')->get();
        $sections = [
            ['department_code' => '01', 'sections' => [
                ['code' => '01', 'name' => '人事課'],
                ['code' => '02', 'name' => '庶務課'],
            ]],
            ['department_code' => '02', 'sections' => [
                ['code' => '01', 'name' => '国内営業課'],
                ['code' => '02', 'name' => '海外営業課'],
            ]],
            ['department_code' => '03', 'sections' => [
                ['code' => '01', 'name' => '開発課'],
                ['code' => '02', 'name' => '品質管理課'],
            ]],
            ['department_code' => '04', 'sections' => [
                ['code' => '01', 'name' => '会計課'],
                ['code' => '02', 'name' => '財務課'],
            ]],
        ];
        foreach ($sections as $depSec) {
            $department = $departments->firstWhere('code', $depSec['department_code']);
            foreach ($depSec['sections'] as $sec) {
                \DB::table('sections')->insert([
                    'department_id' => $department->id,
                    'code' => $sec['code'],
                    'name' => $sec['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
