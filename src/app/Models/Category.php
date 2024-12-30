<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
    ];

    // 親カテゴリー
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // 子カテゴリー
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // カテゴリーに属する商品
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    // カテゴリーの階層構造を取得
    public function getPathAttribute()
    {
        $path = [$this->name];
        $category = $this;

        while ($category->parent) {
            $category = $category->parent;
            array_unshift($path, $category->name);
        }

        return implode(' > ', $path);
    }
}