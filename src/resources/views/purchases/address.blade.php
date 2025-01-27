@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mb-4">住所の変更</h2>

            <form method="POST" action="{{ route('purchases.address.store', $item) }}">
                @csrf

                <div class="mb-3">
                    <label for="postal_code" class="form-label">郵便番号</label>
                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror"
                        id="postal_code" name="postal_code" value="{{ old('postal_code', Auth::user()->postal_code) }}"
                        placeholder="1234567" required>
                    @error('postal_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="address" class="form-label">住所</label>
                    <input type="text"
                        class="form-control @error('address') is-invalid @enderror"
                        id="address"
                        name="address"
                        value="{{ old('address', Auth::user()->address) }}"
                        required>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="building_name" class="form-label">建物名</label>
                    <input type="text"
                        class="form-control"
                        id="building_name"
                        name="building_name"
                        value="{{ old('building_name', Auth::user()->building_name) }}">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-danger btn-lg py-3">
                        更新する
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
