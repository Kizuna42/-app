@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">配送先住所の入力</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('purchases.address.store', $purchase->id) }}">
                        @csrf

                        <div class="mb-3">
                            <label for="postal_code" class="form-label">郵便番号</label>
                            <input id="postal_code" type="text" class="form-control @error('postal_code') is-invalid @enderror" name="postal_code" value="{{ old('postal_code') }}" required>
                            @error('postal_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="prefecture" class="form-label">都道府県</label>
                            <select id="prefecture" class="form-control @error('prefecture') is-invalid @enderror" name="prefecture" required>
                                <option value="">選択してください</option>
                                @foreach($prefectures as $prefecture)
                                    <option value="{{ $prefecture }}" {{ old('prefecture') == $prefecture ? 'selected' : '' }}>
                                        {{ $prefecture }}
                                    </option>
                                @endforeach
                            </select>
                            @error('prefecture')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="city" class="form-label">市区町村</label>
                            <input id="city" type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ old('city') }}" required>
                            @error('city')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">番地・建物名</label>
                            <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" required>
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">電話番号</label>
                            <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <button type="submit" class="btn btn-primary">
                                確認画面へ進む
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 