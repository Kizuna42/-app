@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endsection

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mb-4">会員登録</h2>
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">ユーザー名</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">メールアドレス</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">パスワード</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password-confirm" class="form-label">確認用パスワード</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-danger font-weight-bold">
                        登録する
                    </button>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('login') }}">ログインはこちら</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection