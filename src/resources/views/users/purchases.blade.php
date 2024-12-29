@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">購入履歴</h1>

    @foreach($purchases as $purchase)
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        @if($purchase->item->image)
                            <img src="{{ asset('storage/' . $purchase->item->image) }}" class="img-fluid" alt="{{ $purchase->item->name }}">
                        @endif
                    </div>
                    <div class="col-md-7">
                        <h5 class="card-title">{{ $purchase->item->name }}</h5>
                        <p class="card-text">¥{{ number_format($purchase->item->price) }}</p>
                        <p class="card-text">
                            <small class="text-muted">購入日: {{ $purchase->created_at->format('Y年m月d日') }}</small>
                        </p>
                        <p class="card-text">
                            <small class="text-muted">
                                配送先: 〒{{ $purchase->postal_code }}<br>
                                {{ $purchase->address }}
                            </small>
                        </p>
                    </div>
                    <div class="col-md-3">
                        <div class="d-grid gap-2">
                            <a href="{{ route('items.show', $purchase->item->id) }}" class="btn btn-outline-primary">商品詳細</a>
                            <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-outline-secondary">取引詳細</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="d-flex justify-content-center">
        {{ $purchases->links() }}
    </div>
</div>
@endsection 