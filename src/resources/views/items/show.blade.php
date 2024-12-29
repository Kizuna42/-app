@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" class="img-fluid mb-4" alt="{{ $item->name }}">
            @endif
        </div>
        <div class="col-md-4">
            <h1>{{ $item->name }}</h1>
            <p class="h2 mb-4">¥{{ number_format($item->price) }}</p>
            <div class="mb-4">
                {!! nl2br(e($item->description)) !!}
            </div>
            
            @if($item->user_id !== Auth::id())
                <form action="{{ route('purchases.create', $item->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-lg w-100">購入する</button>
                </form>
            @else
                <div class="d-grid gap-2">
                    <a href="{{ route('items.edit', $item->id) }}" class="btn btn-secondary">編集する</a>
                    <form action="{{ route('items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">削除する</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 