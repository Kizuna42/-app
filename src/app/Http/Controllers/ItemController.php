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
        $items = Item::with(['user', 'category'])
            ->where('is_sold', false)
            ->where('user_id', '!=', Auth::id())
            ->latest()
            ->paginate(12);

        return view('items.index', compact('items'));
    }

    /**
     * マイリストを表示
     */
    public function mylist()
    {
        if (!Auth::check()) {
            return view('items.mylist', ['items' => collect()]);
        }

        $items = Auth::user()->likedItems()
            ->with(['user', 'category'])
            ->latest()
            ->paginate(12);

        return view('items.mylist', compact('items'));
    }

    /**
     * 商品詳細を表示
     */
    public function show(Item $item)
    {
        $item->load(['user', 'category', 'comments.user']);

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
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|max:2048',
        ]);

        $path = $request->file('image')->store('items', 'public');

        $item = Auth::user()->items()->create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'category_id' => $validated['category_id'],
            'image' => $path,
        ]);

        return redirect()->route('items.show', $item)
            ->with('success', '商品を出品しました。');
    }
} 