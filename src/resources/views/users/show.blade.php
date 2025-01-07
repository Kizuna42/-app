@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex align-items-center justify-content-center mb-4">
        @if($user->avatar)
            <img src="{{ asset('storage/' . $user->avatar) }}" class="img-fluid rounded-circle" alt="{{ $user->name }}" style="width: 100px; height: 100px; object-fit: cover;">
        @else
            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                <span class="h1">{{ substr($user->name, 0, 1) }}</span>
            </div>
        @endif
        <h2 class="mx-3">{{ $user->name }}</h2>
        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-outline-danger">プロフィールを編集</a>
    </div>

    <ul class="nav nav-tabs justify-content-center mb-4">
        <li class="nav-item">
            <a class="nav-link active" href="#">出品した商品</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">購入した商品</a>
        </li>
    </ul>

    <div class="row">
        @foreach($items ?? '' as $item)
            <div class="col-md-3 mb-4">
                <div class="card">
                    <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="{{ $item->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->name }}</h5>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection