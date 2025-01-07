<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'user_id',
        'category_id',
        'is_sold',
    ];

    protected $casts = [
        'is_sold' => 'boolean',
        'price' => 'integer',
    ];

    // 出品者
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // カテゴリー
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // いいねしたユーザー
    public function likedUsers()
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }

    // コメント
    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
    }

    // 購入情報
    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }

    // いいね数を取得
    public function getLikesCountAttribute()
    {
        return $this->likedUsers()->count();
    }

    // 指定ユーザーがいいね済みかチェック
    public function isLikedBy($user)
    {
        if ($user === null) {
            return false;
        }
        return $this->likedUsers()->where('user_id', $user->id)->exists();
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}