<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * プロフィール画面を表示
     */
    public function show()
    {
        $user = Auth::user()->load(['items', 'purchases.item']);
        return view('users.show', compact('user'));
    }

    /**
     * プロフィール編集画面を表示
     */
    public function edit()
    {
        $user = Auth::user();
        return view('users.edit', compact('user'));
    }

    /**
     * プロフィールを更新
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.show')
            ->with('success', 'プロフィールを更新しました。');
    }

    /**
     * 購入した商品一覧を表示
     */
    public function purchases()
    {
        $purchases = Auth::user()
            ->purchases()
            ->with(['item.user'])
            ->latest()
            ->paginate(10);

        return view('users.purchases', compact('purchases'));
    }

    /**
     * 出品した商品一覧を表示
     */
    public function items()
    {
        $items = Auth::user()
            ->items()
            ->latest()
            ->paginate(12);

        return view('users.items', compact('items'));
    }
} 