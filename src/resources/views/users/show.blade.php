@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">プロフィール</div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" class="img-fluid rounded-circle" alt="{{ $user->name }}">
                            @else
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 150px; height: 150px;">
                                    <span class="h1">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h2>{{ $user->name }}</h2>
                            <p class="text-muted">登録日: {{ $user->created_at->format('Y年m月d日') }}</p>
                            @if(Auth::id() === $user->id)
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">プロフィールを編集</a>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h3>出品した商品</h3>
                            <div class="list-group">
                                @foreach($user->items as $item)
                                    <a href="{{ route('items.show', $item->id) }}" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">{{ $item->name }}</h5>
                                            <small>¥{{ number_format($item->price) }}</small>
                                        </div>
                                        <small class="text-muted">{{ $item->created_at->format('Y/m/d') }}</small>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        
                        @if(Auth::id() === $user->id)
                            <div class="col-md-6">
                                <h3>購入した商品</h3>
                                <div class="list-group">
                                    @foreach($user->purchases as $purchase)
                                        <a href="{{ route('purchases.show', $purchase->id) }}" class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h5 class="mb-1">{{ $purchase->item->name }}</h5>
                                                <small>¥{{ number_format($purchase->item->price) }}</small>
                                            </div>
                                            <small class="text-muted">{{ $purchase->created_at->format('Y/m/d') }}</small>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 