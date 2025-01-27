<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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
        // 自分の商品は購入できない
        if ($item->user_id === Auth::id()) {
            abort(403, '自分の出品した商品は購入できません。');
        }

        // 売り切れ商品は購入できない
        if ($item->is_sold) {
            abort(403, 'この商品は既に売り切れです。');
        }

        // 商品を購入済みに更新
        $item->update(['is_sold' => true]);

        // 購入記録を作成
        $purchase = Purchase::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'price' => $item->price,
        ]);

        return redirect()->route('purchases.success', $item)
            ->with('success', '商品の購入が完了しました。');
    }

    /**
     * 配送先住所編集フォームを表示
     */
    public function editAddress(Item $item)
    {
        $prefectures = ['北海道', '青森県', '岩手県', /* ... 他の都道府県 ... */];
        return view('purchases.address', compact('item', 'prefectures'));
    }

    /**
     * 配送先住所を更新
     */
    public function updateAddress(Request $request, Item $item)
    {
        $user = Auth::user();
        $user->update([
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building_name' => $request->building
        ]);

        return redirect()->route('purchases.show', $item);
    }

    public function createSession(Item $item)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchases.success', $item),
            'cancel_url' => route('purchases.show', $item),
        ]);

        return response()->json(['id' => $session->id]);
    }

    public function success(Item $item)
    {
        return view('purchases.success', compact('item'));
    }
}
