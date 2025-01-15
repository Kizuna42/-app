<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = Auth::user()->load(['items', 'purchases.item']);
        $items = $user->items;
        return view('users.show', compact('user', 'items'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('users.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'regex:/^\d{3}-?\d{4}$/'],
            'address' => ['required', 'string', 'max:255'],
            'building_name' => ['nullable', 'string', 'max:255'],
        ], [
            'name.required' => 'ユーザー名を入力してください。',
            'postal_code.required' => '郵便番号を入力してください。',
            'postal_code.regex' => '郵便番号は1234567または123-4567の形式で入力してください。',
            'address.required' => '住所を入力してください。',
        ]);

        $validated['postal_code'] = str_replace('-', '', $validated['postal_code']);

        $user->update($validated);

        return redirect()->route('items.index', ['tab' => 'mylist'])
            ->with('success', 'プロフィール情報を更新しました。マイリストをご確認ください。');
    }

    public function purchases()
    {
        $purchases = Auth::user()
            ->purchases()
            ->with(['item.user'])
            ->latest()
            ->paginate(10);

        return view('users.purchases', compact('purchases'));
    }

    public function items()
    {
        $items = Auth::user()
            ->items()
            ->latest()
            ->paginate(12);

        return view('users.items', compact('items'));
    }
}