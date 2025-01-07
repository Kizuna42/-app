<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Item;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(CommentRequest $request, Item $item)
    {
        $comment = Comment::create([
            'content' => $request->content,
            'user_id' => auth()->id(),
            'item_id' => $item->id,
        ]);

        return redirect()->back()->with('success', 'コメントを投稿しました');
    }
} 