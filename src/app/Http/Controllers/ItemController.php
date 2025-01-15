<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * 商品一覧を表示
     */
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'recommend');
        $query = Item::with(['user', 'categories']);

        if ($tab === 'mylist') {
            if (!Auth::check()) {
                return view('items.index', ['items' => collect(), 'tab' => $tab]);
            }
            
            $items = Auth::user()->likedItems()
                ->with(['user', 'categories'])
                ->latest()
                ->paginate(12);
        } else {
            // おすすめ商品（自分の出品以外の商品）
            $query = $query->where('is_sold', false)
                ->where('user_id', '!=', Auth::id());

            if ($search = $request->input('search')) {
                $query->where('name', 'like', "%{$search}%");
            }

            $items = $query->latest()->paginate(12);
        }

        return view('items.index', compact('items', 'tab'));
    }

    /**
     * 商品詳細を表示
     */
    public function show(Item $item)
    {
        $item->load(['user', 'categories', 'comments.user']);

        $likesCount = $item->likes()->count();
        $commentsCount = $item->comments()->count();

        return view('items.show', [
            'item' => $item,
            'likesCount' => $likesCount,
            'commentsCount' => $commentsCount,
        ]);
    }

    /**
     * 商品出品フォームを表示
     */
    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    /**
     * 商品を出品
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer|min:1',
            'condition' => 'required|in:good,fair,poor',
            'categories' => 'required|array|min:1',
            'categories.*' => 'string',
            'image' => 'required|image|max:2048',
        ], [
            'name.required' => '商品名を入力してください',
            'description.required' => '商品の説明を入力してください',
            'price.required' => '価格を入力してください',
            'price.integer' => '価格は整数で入力してください',
            'price.min' => '価格は1円以上で入力してください',
            'condition.required' => '商品の状態を選択してください',
            'categories.required' => 'カテゴリーを選択してください',
            'categories.min' => '1つ以上のカテゴリーを選択してください',
            'image.required' => '商品画像を選択してください',
            'image.image' => '商品画像は画像ファイルを選択してください',
            'image.max' => '商品画像は2MB以下のファイルを選択してください',
        ]);

        try {
            \DB::beginTransaction();

            // 画像のアップロード
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $path = Storage::disk('public')->put('items', $image);
            } else {
                throw new \Exception('画像ファイルのアップロードに失敗しました。');
            }

            // 商品の保存
            $item = new Item([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'condition' => $validated['condition'],
                'image' => Storage::url($path),
                'is_sold' => false,
            ]);

            Auth::user()->items()->save($item);

            // カテゴリーの処理
            $categoryIds = [];
            foreach ($validated['categories'] as $categoryName) {
                $category = Category::firstOrCreate(['name' => $categoryName]);
                $categoryIds[] = $category->id;
            }
            $item->categories()->sync($categoryIds);

            \DB::commit();

            return redirect()->route('items.show', $item)
                ->with('success', '商品を出品しました');

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('商品出品エラー: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            // アップロードした画像を削除
            if (isset($path)) {
                Storage::disk('public')->delete($path);
            }

            return back()
                ->withInput()
                ->with('error', '商品の出品に失敗しました。もう一度お試しください。');
        }
    }
} 