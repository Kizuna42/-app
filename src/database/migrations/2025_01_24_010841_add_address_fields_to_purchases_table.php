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
            // shipping_addressカラムを削除
            $table->dropColumn('shipping_address');

            // 新しい配送先情報カラムを追加
            $table->string('postal_code', 7)->nullable();
            $table->string('prefecture')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('building_name')->nullable();
            $table->string('phone', 15)->nullable();
        });
    }

    /**
     * マイグレーションを戻す
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            // 追加したカラムを削除
            $table->dropColumn([
                'postal_code',
                'prefecture',
                'city',
                'address',
                'building_name',
                'phone'
            ]);

            // shipping_addressカラムを復元
            $table->string('shipping_address');
        });
    }
};