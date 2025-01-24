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
        <a href="{{ route('users.edit') }}" class="btn btn-outline-danger">プロフィールを編集</a>
    </div>

    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link {{ $tab !== 'buy' ? 'active text-danger' : 'text-dark' }}"
                href="{{ route('users.show', ['tab' => 'sell']) }}">
                出品した商品
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'buy' ? 'active text-danger' : 'text-dark' }}"
                href="{{ route('users.show', ['tab' => 'buy']) }}">
                購入した商品
            </a>
        </li>
    </ul>

    @if($items->isEmpty())
        <div class="text-center my-5">
            <p class="text-muted">
                {{ $tab === 'buy' ? '購入した商品はありません。' : '出品した商品はありません。' }}
            </p>
        </div>
    @else
        <div class="row">
            @foreach($items as $item)
                <div class="col-6 col-md-4 col-lg-3 mb-4">
                    <a href="{{ route('items.show', $item) }}" class="text-decoration-none text-dark">
                        <div class="card h-100 border-0 position-relative">
                            @if($item->is_sold)
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background-color: rgba(0, 0, 0, 0.5); z-index: 1;">
                                    <span class="badge bg-danger px-3 py-2 fs-5">SOLD</span>
                                </div>
                            @endif
                            <div class="image-wrapper position-relative" style="padding-top: 100%;">
                                <img src="{{ $item->image }}" class="card-img-top position-absolute top-0 start-0 w-100 h-100" alt="{{ $item->name }}" style="object-fit: cover;">
                            </div>
                            <div class="card-body px-0 py-2">
                                <h5 class="card-title mb-1">{{ $item->name }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection