<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Validation\ValidationException;

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
     * 支払い方法を更新
     */
    public function updatePayment(Request $request, Item $item)
    {
        try {
            $validated = $request->validate([
                'payment_method' => ['required', 'in:credit,convenience'],
            ]);

            $user = auth()->user();
            $user->payment_method = $validated['payment_method'];
            $user->save();

            return response()->json([
                'success' => true,
                'payment_method' => $validated['payment_method'],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'The selected payment method is invalid.',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * 商品を購入
     */
    public function store(Request $request, Item $item)
    {
        // 売り切れ商品は購入できない
        if ($item->is_sold) {
            abort(403, 'この商品は既に売り切れです。');
        }

        $user = Auth::user();

        // 商品を購入済みに更新
        $item->update(['is_sold' => true]);

        // 購入記録を作成
        $purchase = Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'price' => $item->price,
            'postal_code' => $user->postal_code,
            'address' => $user->address,
            'building_name' => $user->building_name,
            'payment_method' => $request->input('payment_method'),
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
        $validated = $request->validate([
            'postal_code' => 'required|digits:7',
            'address' => 'required|string|max:255',
            'building_name' => 'nullable|string|max:255',
        ], [
            'postal_code.digits' => '郵便番号は7桁の数字で入力してください。',
            'postal_code.required' => '郵便番号は必須です。',
            'address.required' => '住所は必須です。',
        ]);

        $user = Auth::user();
        $user->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => '配送先住所を更新しました。']);
        }

        return redirect()->route('purchases.show', $item)
            ->with('success', '配送先住所を更新しました。');
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
