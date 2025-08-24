<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ユーザー情報テーブルの作成
        Schema::create('user_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('usersテーブルのID');
            $table->decimal('paid_leave_remaining', 5, 2)->default(0)->comment('有休残数');
            $table->string('last_name')->comment('姓');
            $table->string('first_name')->comment('名');
            $table->foreignId('department_id')->constrained('departments')->onDelete('restrict')->comment('部署ID');
            $table->foreignId('section_id')->constrained('sections')->onDelete('restrict')->comment('課ID');
            $table->foreignId('position_id')->constrained('positions')->onDelete('restrict')->comment('役職ID');
            $table->foreignId('work_type_id')->constrained('work_types')->onDelete('restrict')->comment('勤務区分ID');
            $table->foreignId('status_id')->constrained('statuses')->onDelete('restrict')->comment('所在状態ID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_infos');
    }
};
