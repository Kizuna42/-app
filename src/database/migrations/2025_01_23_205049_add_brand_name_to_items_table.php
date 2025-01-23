<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * マイグレーション実行
     */
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('brand_name')->nullable()->after('name');
        });
    }

    /**
     * マイグレーションを戻す
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('brand_name');
        });
    }
};
