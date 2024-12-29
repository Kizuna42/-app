@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">プロフィール編集</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <div class="text-center">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" class="img-fluid rounded-circle mb-3" alt="{{ $user->name }}" style="width: 150px; height: 150px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 150px; height: 150px;">
                                        <span class="h1">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="avatar" class="form-label">プロフィール画像</label>
                                <input id="avatar" type="file" class="form-control @error('avatar') is-invalid @enderror" name="avatar" accept="image/*">
                                @error('avatar')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">名前</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">メールアドレス</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">新しいパスワード（変更する場合のみ）</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password-confirm" class="form-label">新しいパスワード（確認）</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                        </div>

                        <div class="mb-0">
                            <button type="submit" class="btn btn-primary">
                                更新する
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 