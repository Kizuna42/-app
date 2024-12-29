<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * 購入確認画面を表示
     */
    public function show(Item $item)
    {
        if ($item->is_sold) {
            return redirect()->route('items.show', $item)
                ->with('error', 'この商品は既に売却済みです。');
        }

        if ($item->user_id === Auth::id()) {
            return redirect()->route('items.show', $item)
                ->with('error', '自分の商品は購入できません。');
        }

        return view('purchases.show', compact('item'));
    }

    /**
     * 商品を購入
     */
    public function store(Request $request, Item $item)
    {
        if ($item->is_sold) {
            return redirect()->route('items.show', $item)
                ->with('error', 'この商品は既に売却済みです。');
        }

        $validated = $request->validate([
            'postal_code' => 'required|string|size:7',
            'prefecture' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string|regex:/^[0-9]{10,11}$/',
        ]);

        DB::transaction(function () use ($item, $validated) {
            $item->update(['is_sold' => true]);

            Purchase::create([
                'user_id' => Auth::id(),
                'item_id' => $item->id,
                'status' => 'pending',
                'postal_code' => $validated['postal_code'],
                'prefecture' => $validated['prefecture'],
                'city' => $validated['city'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
            ]);
        });

        return redirect()->route('users.purchases')
            ->with('success', '商品を購入しました。');
    }

    /**
     * 配送先住所編集フォームを表示
     */
    public function editAddress(Item $item)
    {
        $purchase = $item->purchase;
        
        if (!$purchase || $purchase->user_id !== Auth::id()) {
            abort(404);
        }

        return view('purchases.address', compact('purchase'));
    }

    /**
     * 配送先住所を更新
     */
    public function updateAddress(Request $request, Item $item)
    {
        $purchase = $item->purchase;
        
        if (!$purchase || $purchase->user_id !== Auth::id()) {
            abort(404);
        }

        $validated = $request->validate([
            'postal_code' => 'required|string|size:7',
            'prefecture' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string|regex:/^[0-9]{10,11}$/',
        ]);

        $purchase->update($validated);

        return redirect()->route('purchases.show', $item)
            ->with('success', '配送先住所を更新しました。');
    }
} 