<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand_name',
        'description',
        'price',
        'image',
        'condition',
        'user_id',
        'is_sold',
    ];

    protected $casts = [
        'is_sold' => 'boolean',
        'price' => 'integer',
    ];

    // 商品状態の定数
    const CONDITION_GOOD = 'good';
    const CONDITION_FAIR = 'fair';
    const CONDITION_POOR = 'poor';

    // 商品状態の表示名
    const CONDITION_NAMES = [
        self::CONDITION_GOOD => '目立った傷や汚れなし',
        self::CONDITION_FAIR => 'やや傷や汚れあり',
        self::CONDITION_POOR => '状態が悪い',
    ];

    // リレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'item_category');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
    }

    // アクセサ
    public function getConditionNameAttribute()
    {
        return self::CONDITION_NAMES[$this->condition] ?? $this->condition;
    }

    // いいね関連のメソッド
    public function isLikedBy($user)
    {
        if ($user === null) {
            return false;
        }
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    public function toggleLike($user)
    {
        if ($this->isLikedBy($user)) {
            return $this->likes()->where('user_id', $user->id)->delete();
        } else {
            return $this->likes()->create(['user_id' => $user->id]);
        }
    }
}