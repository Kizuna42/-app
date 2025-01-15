@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- 商品画像 -->
        <div class="col-md-6">
            @if($item->image)
                <img src="{{ $item->image }}" class="img-fluid" alt="{{ $item->name }}">
            @else
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 400px;">
                    <span>商品画像</span>
                </div>
            @endif
        </div>

        <!-- 商品情報 -->
        <div class="col-md-6">
            <h1 class="product-title">{{ $item->name }}</h1>
            <p class="brand-name">
                {{ $item->categories->pluck('name')->join('、') }}
            </p>

            <p class="price-text">¥{{ number_format($item->price) }}<span class="tax-included">(税込)</span></p>

            <div class="d-flex justify-content-start mb-4">
                <div class="text-center me-4">
                    <button class="btn like-button d-block mx-auto" data-item-id="{{ $item->id }}" style="border: none; background: none; padding: 0;">
                        <i class="fa-star fa-lg {{ $item->isLikedBy(auth()->user()) ? 'fas text-warning' : 'far text-secondary' }}"></i>
                    </button>
                    <span class="d-block mt-1" style="min-width: 30px">{{ $item->likes_count }}</span>
                </div>
                <div class="text-center">
                    <div class="d-block mx-auto">
                        <i class="far fa-comment fa-lg text-secondary"></i>
                    </div>
                    <span class="d-block mt-1" style="min-width: 30px">{{ $commentsCount }}</span>
                </div>
            </div>

            <a href="{{ route('purchases.show', $item) }}" class="btn btn-danger w-100 mb-5">購入手続きへ</a>

            <div class="product-section mb-4">
                <h4 class="section-title mb-3">商品説明</h4>
                <p class="description">{{ $item->description }}</p>
            </div>

            <div class="product-section mb-4">
                <h4 class="section-title mb-3">商品の情報</h4>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="label">カテゴリー</span>
                        <div>
                            @foreach($item->categories as $category)
                                <span class="badge bg-light text-dark me-1">{{ $category->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="info-item">
                        <span class="label">商品の状態</span>
                        <span class="badge bg-light text-dark">{{ $item->condition_name }}</span>
                    </div>
                </div>
            </div>

            <div class="product-section">
                <h4 class="section-title mb-3">コメント ({{ $commentsCount }})</h4>
                <div class="comments-container bg-light p-3">
                    @if($item->comments->isEmpty())
                        <p class="text-center mb-0">こちらにコメントが入ります。</p>
                    @else
                        @foreach($item->comments as $comment)
                            <div class="comment-item mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="commenter-info">
                                        <strong>{{ $comment->user->name }}</strong>
                                    </div>
                                </div>
                                <p class="comment-text mb-0">{{ $comment->content }}</p>
                            </div>
                        @endforeach
                    @endif
                </div>

                @auth
                    <form method="POST" action="{{ route('items.comments.store', $item) }}" class="mt-4">
                        @csrf
                        <div class="mb-3">
                            <label for="comment" class="form-label">商品へのコメント</label>
                            <textarea class="form-control" id="comment" name="content" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">コメントを送信する</button>
                    </form>
                @else
                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="btn btn-outline-danger">ログインしてコメントする</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const likeButton = document.querySelector('.like-button');
    if (likeButton) {
        likeButton.addEventListener('click', async function() {
            @auth
                const itemId = this.dataset.itemId;
                const starIcon = this.querySelector('i');
                const likesCount = this.querySelector('.likes-count');

                try {
                    const response = await fetch(`/items/${itemId}/like`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    // いいねの状態を更新
                    if (data.is_liked) {
                        starIcon.classList.remove('far');
                        starIcon.classList.add('fas');
                    } else {
                        starIcon.classList.remove('fas');
                        starIcon.classList.add('far');
                    }

                    // いいね数を更新
                    likesCount.textContent = data.likes_count;

                } catch (error) {
                    console.error('Error:', error);
                }
            @else
                window.location.href = '{{ route('login') }}';
            @endauth
        });
    }
});
</script>
@endpush
@endsection