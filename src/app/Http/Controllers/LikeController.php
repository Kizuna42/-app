<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function toggle(Item $item)
    {
        $user = auth()->user();
        
        if ($item->isLikedBy($user)) {
            $item->likes()->where('user_id', $user->id)->delete();
            $action = 'unliked';
        } else {
            $item->likes()->create(['user_id' => $user->id]);
            $action = 'liked';
        }

        return response()->json([
            'likes_count' => $item->fresh()->likes_count,
            'is_liked' => $action === 'liked'
        ]);
    }
} 