@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">購入確認</h1>
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" class="img-fluid" alt="{{ $item->name }}">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h5 class="card-title">{{ $item->name }}</h5>
                            <p class="card-text">¥{{ number_format($item->price) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">配送先住所</h5>
                    <form action="{{ route('purchases.store', $item->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="postal_code" class="form-label">郵便番号</label>
                            <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" required>
                            @error('postal_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">住所</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">購入を確定する</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">購入金額</h5>
                    <p class="h3">¥{{ number_format($item->price) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 