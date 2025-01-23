<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function toggle(Item $item)
    {
        $user = Auth::user();

        // いいねの切り替え
        $item->toggleLike($user);

        // 更新後のいいね状態を返す
        return response()->json([
            'is_liked' => $item->isLikedBy($user),
            'likes_count' => $item->likes()->count()
        ]);
    }
}