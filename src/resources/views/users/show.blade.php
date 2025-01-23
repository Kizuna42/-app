@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex align-items-center justify-content-center mb-4">
        @if($user->avatar)
            <img src="{{ asset('storage/avatars/' . $user->avatar) }}" class="img-fluid rounded-circle" alt="{{ $user->name }}" style="width: 100px; height: 100px; object-fit: cover;">
        @else
            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                <span class="h1">{{ substr($user->name, 0, 1) }}</span>
            </div>
        @endif
        <h2 class="mx-3">{{ $user->name }}</h2>
        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-outline-danger">プロフィールを編集</a>
    </div>

    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link {{ request('tab') !== 'purchased' ? 'active text-danger' : 'text-dark' }}"
                href="{{ route('users.show', ['user' => $user->id, 'tab' => 'listed']) }}">
                出品した商品
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('tab') === 'purchased' ? 'active text-danger' : 'text-dark' }}"
                href="{{ route('users.show', ['user' => $user->id, 'tab' => 'purchased']) }}">
                購入した商品
            </a>
        </li>
    </ul>

    <div class="row">
        @if($items->isEmpty())
            <div class="col-12 text-center">
                <p class="text-muted">
                    {{ request('tab') === 'purchased' ? '購入した商品はありません。' : '出品した商品はありません。' }}
                </p>
            </div>
        @else
            @foreach($items as $item)
                <div class="col-md-3 mb-4">
                    <a href="{{ route('items.show', $item) }}" class="text-decoration-none">
                        <div class="card h-100">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="{{ $item->name }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <span>No Image</span>
                                </div>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title text-dark">{{ $item->name }}</h5>
                                <p class="card-text text-danger">¥{{ number_format($item->price) }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection