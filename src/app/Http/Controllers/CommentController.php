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

            if ($request->expectsJson()) {
                $user = auth()->user();
                return response()->json([
                    'success' => true,
                    'message' => 'コメントを投稿しました',
                    'content' => $comment->content,
                    'user_name' => $user->name,
                    'user_avatar' => $user->avatar,
                    'created_at' => $comment->created_at->format('Y/m/d H:i'),
                    'comments_count' => $commentsCount
                ]);
            }

            return redirect()->back()
                ->with('success', 'コメントを投稿しました')
                ->with('commentsCount', $commentsCount);

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'コメントの投稿に失敗しました'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'コメントの投稿に失敗しました')
                ->withInput();
        }
    }
}