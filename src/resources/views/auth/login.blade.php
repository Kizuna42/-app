@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-center mb-4">ログイン</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">ユーザー名 / メールアドレス</label>
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

                <div class="d-grid">
                    <button type="submit" class="btn btn-danger">
                        ログインする
                    </button>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('register') }}">会員登録はこちら</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection