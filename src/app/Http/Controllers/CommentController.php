<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Item;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(CommentRequest $request, Item $item)
    {
        try {
            DB::beginTransaction();

            $comment = Comment::create([
                'content' => $request->content,
                'user_id' => auth()->id(),
                'item_id' => $item->id,
            ]);

            // コメント数を更新するためにitemを再取得
            $item->refresh();
            $commentsCount = $item->comments()->count();

            DB::commit();

            return redirect()->back()
                ->with('success', 'コメントを投稿しました')
                ->with('commentsCount', $commentsCount);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'コメントの投稿に失敗しました')
                ->withInput();
        }
    }
}