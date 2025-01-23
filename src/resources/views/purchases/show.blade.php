@extends('layouts.app')

@section('content')
<div class="container-fluid px-5 mt-4">
    <div class="row gx-5">
        <!-- 左側：商品情報 -->
        <div class="col-md-8">
            <div class="mb-5 pb-4 border-bottom">
                <div class="row align-items-center">
                    <div class="col-md-5">
                        @if($item->image)
                            <img src="{{ $item->image }}" class="img-fluid" alt="{{ $item->name }}">
                        @endif
                    </div>
                    <div class="col-md-7">
                        <h3 class="mb-3">{{ $item->name }}</h3>
                        <h4 class="mb-0">¥{{ number_format($item->price) }}</h4>
                    </div>
                </div>
            </div>

            <!-- フォームを上部に移動 -->
            <form action="{{ route('purchases.store', $item) }}" method="POST" id="purchase-form">
                @csrf
                <!-- 支払い方法 -->
                <div class="mb-5 pb-4 border-bottom">
                    <h4 class="mb-4">支払い方法</h4>
                    <select class="form-select form-select-lg border-0 ps-0" id="payment_method" name="payment_method" required>
                        <option value="" selected disabled>選択してください</option>
                        <option value="convenience">コンビニ払い</option>
                        <option value="credit">カード支払い</option>
                    </select>
                </div>

                <!-- 配送先 -->
                <div class="mb-5 pb-4 border-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">配送先</h4>
                        <a href="{{ route('purchases.address.edit', $item) }}" class="text-primary text-decoration-none">変更する</a>
                    </div>
                    @if(auth()->user()->postal_code && auth()->user()->address)
                        <p class="mb-2 fs-5">〒{{ substr(auth()->user()->postal_code, 0, 3) }}-{{ substr(auth()->user()->postal_code, 3) }}</p>
                        <p class="mb-0 fs-5">{{ auth()->user()->address }}</p>
                        <p class="mb-0 fs-5">{{ auth()->user()->building_name }}</p>
                    @else
                        <p class="text-muted mb-0 fs-5">配送先住所を登録してください</p>
                    @endif
                </div>
            </form>
        </div>

        <!-- 右側：購入確認 -->
        <div class="col-md-4">
            <div class="position-sticky" style="top: 2rem;">
                <!-- 金額・支払い方法の確認 -->
                <div class="border rounded mb-4">
                    <div class="border-bottom p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fs-5">商品代金</span>
                            <span class="h4 mb-0">¥{{ number_format($item->price) }}</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fs-5">支払い方法</span>
                            <span class="h4 mb-0" id="selected-payment">選択してください</span>
                        </div>
                    </div>
                </div>

                <!-- 購入ボタン -->
                <button type="submit" form="purchase-form" class="btn btn-danger btn-lg w-100 py-3 rounded-3" id="purchase-button" disabled>
                    購入する
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
// DOMの読み込みを待つ
window.addEventListener('load', function() {
    // 要素の取得
    const form = document.getElementById('purchase-form');
    const select = document.getElementById('payment_method');
    const button = document.getElementById('purchase-button');
    const paymentDisplay = document.getElementById('selected-payment');

    // 住所の状態
    const hasValidAddress = {{ auth()->user()->postal_code && auth()->user()->address ? 'true' : 'false' }};

    // デバッグ用ログ
    console.log('初期状態:', {
        'フォーム': form ? '存在します' : '見つかりません',
        'セレクト': select ? '存在します' : '見つかりません',
        'ボタン': button ? '存在します' : '見つかりません',
        '住所': hasValidAddress ? '登録済み' : '未登録'
    });

    // セレクトの変更イベント
    select.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];

        // 選択された支払い方法を表示
        paymentDisplay.textContent = selectedOption.text;

        // ボタンの有効化
        if (hasValidAddress && this.value) {
            button.disabled = false;
            console.log('ボタンを有効化しました');
        } else {
            button.disabled = true;
            console.log('ボタンを無効化しました');
        }
    });

    // フォームの送信
    form.addEventListener('submit', async function(event) {
        event.preventDefault();

        const paymentMethod = select.value;
        console.log('送信開始:', paymentMethod);

        if (paymentMethod === 'credit') {
            button.disabled = true;

            try {
                const response = await fetch('{{ route('purchases.store', $item) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ payment_method: paymentMethod })
                });

                const data = await response.json();

                if (data.error) {
                    alert(data.error);
                    button.disabled = false;
                    return;
                }

                const stripe = Stripe('{{ config('services.stripe.key') }}');
                const result = await stripe.redirectToCheckout({
                    sessionId: data.sessionId
                });

                if (result.error) {
                    alert(result.error.message);
                    button.disabled = false;
                }
            } catch (error) {
                alert('決済処理中にエラーが発生しました。');
                button.disabled = false;
            }
        } else {
            form.submit();
        }
    });
});
</script>
@endpush
@endsection