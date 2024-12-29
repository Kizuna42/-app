<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id',
        'item_id',
    ];

    // コメントしたユーザー
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // コメントされた商品
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // 返信コメント
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    // 親コメント
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
} 