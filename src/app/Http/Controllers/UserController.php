<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        $request->validate([
            'name' => 'required|string|max:255',
            'postal_code' => 'required|string|size:7',
            'address' => 'required|string|max:255',
            'building_name' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('avatar')) {
            // 古い画像の削除処理
            if ($request->user()->avatar) {
                Storage::disk('public')->delete('avatars/' . $request->user()->avatar);
            }

            // 新しい画像の保存
            $avatar = $request->file('avatar');
            $filename = time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();
            
            // 画像を保存
            $path = $avatar->storeAs('avatars', $filename, 'public');
            
            // データベースにファイル名を保存
            $request->user()->avatar = $filename;
        }

        $request->user()->update([
            'name' => $request->name,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building_name' => $request->building_name,
        ]);

        return redirect()->route('users.show', $request->user()->id)
            ->with('success', 'プロフィールを更新しました。');
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