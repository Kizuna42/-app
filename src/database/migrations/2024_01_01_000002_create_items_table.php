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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('name');
            $table->integer('price');
            $table->text('description');
            $table->string('image');
            $table->string('condition');
            $table->boolean('is_sold')->default(false);
            $table->timestamps();
        });

        // 中間テーブルの作成
        Schema::create('item_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['item_id', 'category_id']);
        });
    }

    /**
     * マイグレーションを戻す
     */
    public function down(): void
    {
        Schema::dropIfExists('item_category');
        Schema::dropIfExists('items');
    }
};