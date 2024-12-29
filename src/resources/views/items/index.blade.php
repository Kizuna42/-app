@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">商品一覧</h1>
    <div class="row">
        @foreach($items as $item)
            <div class="col-md-4 mb-4">
                <div class="card">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="{{ $item->name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->name }}</h5>
                        <p class="card-text">¥{{ number_format($item->price) }}</p>
                        <a href="{{ route('items.show', $item->id) }}" class="btn btn-primary">詳細を見る</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection 