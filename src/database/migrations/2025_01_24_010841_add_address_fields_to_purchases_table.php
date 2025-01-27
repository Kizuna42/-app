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
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('postal_code')->nullable();
            $table->string('prefecture')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('building_name')->nullable();
        });
    }

    /**
     * マイグレーションを戻す
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn([
                'postal_code',
                'prefecture',
                'city',
                'address',
                'building_name',
            ]);
        });
    }
};
