@extends('layouts.app')

@section('content')
<div class="container mt-5">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            @if($item->image)
                <img src="{{ $item->image }}" class="img-fluid mb-4" alt="{{ $item->name }}">
            @else
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 100%; height: 300px;">
                    <span>商品画像</span>
                </div>
            @endif
        </div>
        <div class="col-md-6">
            <h1 class="mb-4">{{ $item->name }}</h1>
            <p class="text-muted">{{ $item->brand }}</p>
            <p class="h2">¥{{ number_format($item->price) }} (税込)</p>
            <div class="d-flex align-items-center mb-4">
                <button class="btn me-3 like-button" data-item-id="{{ $item->id }}" style="border: none; background: none; padding: 0;">
                    <i class="fa-star fa-lg me-1 {{ $item->isLikedBy(auth()->user()) ? 'fas text-warning' : 'far text-secondary' }}"></i>
                    <span class="likes-count">{{ $item->likes_count }}</span>
                </button>
                <span><i class="far fa-comment fa-lg text-secondary"></i> {{ $item->comments_count }}</span>
            </div>
            <button class="btn btn-danger btn-lg w-100 mb-4">購入手続きへ</button>
            <h4>商品説明</h4>
            <p>{{ $item->description }}</p>
            <h4>商品の情報</h4>
            <p>カテゴリー: {{ $item->category->name }}</p>
            <p>商品の状態: {{ $item->condition }}</p>
            <h4>コメント ({{ $item->comments_count }})</h4>
            <div class="seller-info mb-4">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('storage/' . $item->user->avatar) }}" class="rounded-circle me-3" style="width: 40px; height: 40px;" alt="{{ $item->user->name }}">
                    <div>
                        <strong>{{ $item->user->name }}</strong>
                        <span class="badge bg-primary ms-2">出品者</span>
                    </div>
                </div>
            </div>

            <div class="comments-section mb-4">
                @if($item->comments->count() > 0)
                    @foreach($item->comments as $comment)
                        <div class="comment-item mb-3 p-3 border rounded">
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ asset('storage/' . $comment->user->avatar) }}" class="rounded-circle me-3" style="width: 40px; height: 40px;" alt="{{ $comment->user->name }}">
                                <div>
                                    <strong>{{ $comment->user->name }}</strong>
                                    <small class="text-muted ms-2">{{ $comment->created_at->format('Y/m/d H:i') }}</small>
                                </div>
                            </div>
                            <p class="mb-0 ms-5">{{ $comment->content }}</p>
                        </div>
                    @endforeach
                @else
                    <p class="text-center text-muted">まだコメントはありません</p>
                @endif
            </div>

            <div class="comment-form">
                <form method="POST" action="{{ route('items.comments.store', $item->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="comment" class="form-label">商品へのコメント</label>
                        <textarea class="form-control" id="comment" name="content" rows="3" placeholder="商品に関するコメントを入力してください"></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">コメントを送信する</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const likeButtons = document.querySelectorAll('.like-button');
    
    likeButtons.forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            
            // 未ログインの場合はログインページへリダイレクト
            if (!@json(auth()->check())) {
                window.location.href = '{{ route('login') }}';
                return;
            }

            const itemId = this.dataset.itemId;
            const starIcon = this.querySelector('i');
            const likesCount = this.querySelector('.likes-count');
            
            try {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch(`/items/${itemId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                
                // いいね数を更新
                likesCount.textContent = data.likes_count;
                
                // スターの色とスタイルを切り替え
                if (data.is_liked) {
                    starIcon.classList.remove('far', 'text-secondary');
                    starIcon.classList.add('fas', 'text-warning');
                } else {
                    starIcon.classList.remove('fas', 'text-warning');
                    starIcon.classList.add('far', 'text-secondary');
                }

                // クリックフィードバックのアニメーション
                button.classList.add('animate__animated', 'animate__pulse');
                setTimeout(() => {
                    button.classList.remove('animate__animated', 'animate__pulse');
                }, 500);
                
            } catch (error) {
                console.error('Error:', error);
                alert('エラーが発生しました。もう一度お試しください。');
            }
        });
    });
});
</script>
@endpush
@endsection