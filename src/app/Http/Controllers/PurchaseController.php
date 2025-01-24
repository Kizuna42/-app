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

        $user = Auth::user();
        if (!$user->postal_code || !$user->address) {
            return redirect()->route('purchases.address.edit', $item)
                ->with('error', '配送先住所を登録してください。');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:convenience,credit',
        ]);

        // カード支払いの場合はStripe決済を実行
        if ($validated['payment_method'] === 'credit') {
            try {
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

                return response()->json(['sessionId' => $session->id]);
            } catch (\Exception $e) {
                return response()->json(['error' => '決済処理に失敗しました。'], 500);
            }
        }

        // コンビニ支払いの場合は購入情報を保存
        DB::transaction(function () use ($item, $validated, $user) {
            $item->update(['is_sold' => true]);

            Purchase::create([
                'user_id' => Auth::id(),
                'item_id' => $item->id,
                'payment_method' => $validated['payment_method'],
                'status' => 'pending',
                'postal_code' => $user->postal_code,
                'prefecture' => $user->prefecture,
                'city' => $user->city,
                'address' => $user->address,
                'building_name' => $user->building_name,
            ]);
        });

        return redirect()->route('users.purchases')
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
}