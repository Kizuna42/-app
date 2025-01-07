@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <ul class="nav nav-tabs justify-content-center mb-4">
        <li class="nav-item">
            <a class="nav-link {{ request('tab') !== 'mylist' ? 'active' : '' }}" href="{{ route('items.index', ['tab' => 'recommend']) }}">おすすめ</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('tab') === 'mylist' ? 'active' : '' }}" href="{{ route('items.index', ['tab' => 'mylist']) }}">マイリスト</a>
        </li>
    </ul>

    <div class="row">
        @foreach($items as $item)
            <div class="col-md-3 mb-4">
                <div class="card">
                    <img src="{{ $item->image }}" class="card-img-top" alt="{{ $item->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->name }}</h5>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection