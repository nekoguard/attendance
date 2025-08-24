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
        // 部署マスタテーブルの作成
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('code', 2)->unique()->comment('部署コード');
            $table->string('name')->comment('部署名');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
