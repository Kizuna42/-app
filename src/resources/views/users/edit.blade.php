@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mb-4">プロフィール設定</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('users.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">ユーザー名</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="postal_code" class="form-label">郵便番号</label>
                    <input id="postal_code" type="text" class="form-control @error('postal_code') is-invalid @enderror"
                        name="postal_code"
                        value="{{ old('postal_code', $user->postal_code) }}"
                        placeholder="1234567"
                        required>
                    @error('postal_code')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">住所</label>
                    <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address', $user->address) }}" required>
                    @error('address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="building_name" class="form-label">建物名</label>
                    <input id="building_name" type="text" class="form-control @error('building_name') is-invalid @enderror" name="building_name" value="{{ old('building_name', $user->building_name) }}">
                    @error('building_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-danger">
                        更新する
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection