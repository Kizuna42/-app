@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- タブ切り替え -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link {{ request('tab') !== 'mylist' ? 'active text-danger' : 'text-dark' }}"
                href="{{ route('items.index', ['tab' => 'recommend']) }}">
                おすすめ
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('tab') === 'mylist' ? 'active text-danger' : 'text-dark' }}"
                href="{{ route('items.index', ['tab' => 'mylist']) }}">
                マイリスト
            </a>
        </li>
    </ul>

    @if($items->isEmpty())
        <div class="text-center my-5">
            @if($tab === 'mylist')
                @auth
                    <p>まだいいねした商品がありません。</p>
                @else
                    <p>マイリストを見るにはログインが必要です。</p>
                    <a href="{{ route('login') }}" class="btn btn-primary text-white">ログイン</a>
                @endauth
            @else
                <p>商品が見つかりませんでした。</p>
            @endif
        </div>
    @else
        <div class="row g-4">
            @foreach($items as $item)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <a href="{{ route('items.show', $item) }}" class="text-decoration-none text-dark">
                        <div class="card h-100 border-0 position-relative item-card">
                            @if($item->is_sold)
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background-color: rgba(0, 0, 0, 0.5); z-index: 1;">
                                    <span class="badge bg-danger px-3 py-2 fs-5">SOLD</span>
                                </div>
                            @endif
                            <div class="image-wrapper position-relative" style="padding-top: 100%;">
                                <img src="{{ $item->image }}" class="card-img-top position-absolute top-0 start-0 w-100 h-100" alt="{{ $item->name }}" style="object-fit: cover;">
                            </div>
                            <div class="card-body px-0 py-2">
                                <h5 class="card-title mb-1 text-truncate">{{ $item->name }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $items->links() }}
        </div>
    @endif
</div>

<style>
@media (min-width: 768px) and (max-width: 850px) {
    .container {
        max-width: 720px;
    }
    .item-card .card-title {
        font-size: 0.9rem;
    }
}

@media (min-width: 1400px) and (max-width: 1540px) {
    .container {
        max-width: 1320px;
    }
}

.item-card {
    transition: transform 0.2s;
}

.item-card:hover {
    transform: translateY(-5px);
}
</style>
@endsection