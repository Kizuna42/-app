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
            @if($item->brand_name)
                <p class="brand-name">{{ $item->brand_name }}</p>
            @endif
            <p class="price-text">¥{{ number_format($item->price) }}<span class="tax-included">(税込)</span></p>

            <div class="d-flex justify-content-start mb-4">
                <div class="text-center me-4">
                    <button class="btn like-button d-block mx-auto" data-item-id="{{ $item->id }}" style="border: none; background: none; padding: 0;">
                        <i class="fa-star fa-lg {{ $item->isLikedBy(auth()->user()) ? 'fas text-warning' : 'far text-secondary' }}"></i>
                        <span class="likes-count d-block mt-1" style="min-width: 30px">{{ $item->likes_count }}</span>
                    </button>
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
                                <span class="badge bg-light text-dark me-1">
                                    @if($category->parent)
                                        {{ $category->parent->name }} > {{ $category->name }}
                                    @else
                                        {{ $category->name }}
                                    @endif
                                </span>
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
                <h4 class="section-title mb-3">コメント (<span id="comments-count">{{ $commentsCount }}</span>)</h4>
                <div class="comments-container bg-light p-3">
                    @if($item->comments->isEmpty())
                        <p class="text-center mb-0" id="no-comments-message">こちらにコメントが入ります。</p>
                    @endif
                    <div id="comments-list">
                        @foreach($item->comments as $comment)
                            <div class="comment-item mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="me-2">
                                        @if($comment->user->avatar)
                                            <img src="{{ asset('storage/avatars/' . $comment->user->avatar) }}" alt="{{ $comment->user->name }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <span>{{ substr($comment->user->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="commenter-info">
                                            <strong>{{ $comment->user->name }}</strong>
                                            <small class="text-muted ms-2">{{ $comment->created_at->format('Y/m/d H:i') }}</small>
                                        </div>
                                        <p class="comment-text mb-0">{{ $comment->content }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @auth
                    <form method="POST" action="{{ route('items.comments.store', $item) }}" class="mt-4" id="comment-form" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="comment" class="form-label">商品へのコメント</label>
                            <textarea 
                                class="form-control @error('content') is-invalid @enderror" 
                                id="comment" 
                                name="content" 
                                rows="3" 
                                required 
                                maxlength="255"
                            >{{ old('content') }}</textarea>
                            <div class="text-muted small mt-1">
                                <span id="char-count">0</span>/255文字
                            </div>
                            @error('content')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
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
                const likesCountElement = this.querySelector('.likes-count');

                try {
                    const response = await fetch(`/items/${itemId}/like`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const data = await response.json();

                    // いいねの状態を更新
                    if (data.is_liked) {
                        starIcon.classList.remove('far', 'text-secondary');
                        starIcon.classList.add('fas', 'text-warning');
                    } else {
                        starIcon.classList.remove('fas', 'text-warning');
                        starIcon.classList.add('far', 'text-secondary');
                    }

                    // いいね数を更新
                    likesCountElement.textContent = data.likes_count;

                } catch (error) {
                    console.error('Error:', error);
                    alert('いいねの処理中にエラーが発生しました。');
                }
            @else
                window.location.href = '{{ route('login') }}';
            @endauth
        });
    }
});

// コメントフォームの文字数カウント機能
document.addEventListener('DOMContentLoaded', function() {
    const commentForm = document.getElementById('comment-form');
    const textarea = document.getElementById('comment');
    const charCount = document.getElementById('char-count');

    if (textarea && charCount) {
        textarea.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;

            if (count > 255) {
                textarea.classList.add('is-invalid');
                charCount.classList.add('text-danger');
            } else {
                textarea.classList.remove('is-invalid');
                charCount.classList.remove('text-danger');
            }
        });
    }

    if (commentForm) {
        commentForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'コメントの送信に失敗しました。');
                }

                // コメントリストを更新
                const commentsList = document.getElementById('comments-list');
                const noCommentsMessage = document.getElementById('no-comments-message');
                const commentsCount = document.getElementById('comments-count');

                // 新しいコメントのHTML
                const newComment = `
                    <div class="comment-item mb-3">
                        <div class="d-flex align-items-start">
                            <div class="me-2">
                                ${data.user_avatar 
                                    ? `<img src="${data.user_avatar.startsWith('/') ? '' : '/'}storage/${data.user_avatar}" alt="${data.user_name}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">`
                                    : `<div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <span>${data.user_name.charAt(0)}</span>
                                       </div>`
                                }
                            </div>
                            <div class="flex-grow-1">
                                <div class="commenter-info">
                                    <strong>${data.user_name}</strong>
                                    <small class="text-muted ms-2">${data.created_at}</small>
                                </div>
                                <p class="comment-text mb-0">${data.content}</p>
                            </div>
                        </div>
                    </div>
                `;

                // "コメントがありません"メッセージを非表示
                if (noCommentsMessage) {
                    noCommentsMessage.style.display = 'none';
                }

                // 新しいコメントを追加
                commentsList.insertAdjacentHTML('beforeend', newComment);

                // コメント数を更新
                commentsCount.textContent = data.comments_count;

                // フォームをリセット
                this.reset();
                charCount.textContent = '0';

            } catch (error) {
                console.error('Error:', error);
                alert(error.message);
            }
        });
    }
});
</script>
@endpush
@endsection