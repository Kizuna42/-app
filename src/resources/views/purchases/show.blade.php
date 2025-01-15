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

            <!-- 支払い方法 -->
            <div class="mb-5 pb-4 border-bottom">
                <h4 class="mb-4">支払い方法</h4>
                <select class="form-select form-select-lg border-0 ps-0 @error('payment_method') is-invalid @enderror" name="payment_method" required>
                    <option value="" selected disabled>選択してください</option>
                    <option value="convenience">コンビニ払い</option>
                    <option value="credit">カード支払い</option>
                </select>
                @error('payment_method')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
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
                @else
                    <p class="text-muted mb-0 fs-5">配送先住所を登録してください</p>
                @endif
            </div>
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
                            <span id="selected-payment" class="text-muted fs-5">選択してください</span>
                        </div>
                    </div>
                </div>

                <!-- 購入ボタン -->
                <form action="{{ route('purchases.store', $item) }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" id="payment_method_hidden">
                    <button type="submit" class="btn btn-danger btn-lg w-100 py-3 rounded-3">購入する</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.querySelector('select[name="payment_method"]').addEventListener('change', function() {
    const selectedText = this.options[this.selectedIndex].text;
    document.getElementById('selected-payment').textContent = selectedText;
    document.getElementById('payment_method_hidden').value = this.value;
});
</script>
@endpush
@endsection