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
                    <a href="{{ route('login') }}" class="btn btn-primary">ログイン</a>
                @endauth
            @else
                <p>商品が見つかりませんでした。</p>
            @endif
        </div>
    @else
        <div class="row">
            @foreach($items as $item)
                <div class="col-6 col-md-4 col-lg-3 mb-4">
                    <a href="{{ route('items.show', $item) }}" class="text-decoration-none text-dark">
                        <div class="card h-100 border-0 position-relative">
                            @if($item->is_sold)
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background-color: rgba(0, 0, 0, 0.5);">
                                    <span class="badge bg-danger px-3 py-2 fs-5">SOLD</span>
                                </div>
                            @endif
                            <img src="{{ $item->image }}" class="card-img-top" alt="{{ $item->name }}" style="object-fit: cover;">
                            <div class="card-body px-0 py-2">
                                <h5 class="card-title mb-0">{{ $item->name }}</h5>
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
@endsection