<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * ログインフォームを表示
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * ログイン処理
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        if (!Auth::user()->hasVerifiedEmail()) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'メール認証が完了していません。']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('items.index'))
            ->with('success', 'ログインしました。');
    }

    /**
     * ログアウト処理
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('items.index')
            ->with('success', 'ログアウトしました。');
    }
} 