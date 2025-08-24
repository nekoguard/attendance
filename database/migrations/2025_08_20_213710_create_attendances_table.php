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
        // 勤怠テーブルの作成
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('ユーザーID');
            $table->date('date')->comment('日付');
            $table->dateTime('clock_in_at')->nullable()->comment('出勤日時');
            $table->dateTime('clock_out_at')->nullable()->comment('退勤日時');
            $table->dateTime('break_start_at')->nullable()->comment('外出日時');
            $table->dateTime('break_end_at')->nullable()->comment('戻り日時');
            $table->unsignedBigInteger('status_id')->nullable()->comment('所在状態ID');
            $table->foreign('status_id')->references('id')->on('statuses');
            $table->integer('working_time')->nullable()->comment('勤務時間（分単位）');
            $table->integer('overtime_minutes')->nullable()->comment('残業時間（分単位）');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
