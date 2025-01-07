@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">出品した商品</h1>
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
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('items.show', $item->id) }}" class="btn btn-primary">詳細</a>
                            <a href="{{ route('items.edit', $item->id) }}" class="btn btn-secondary">編集</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection