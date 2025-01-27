@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mb-4">住所の変更</h2>

            <form id="address-form" method="POST" action="{{ route('purchases.address.store', $item) }}">
                @csrf

                <div class="mb-3">
                    <label for="postal_code" class="form-label">郵便番号</label>
                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror"
                        id="postal_code" name="postal_code" value="{{ old('postal_code', Auth::user()->postal_code) }}"
                        placeholder="1234567" required>
                    @error('postal_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="address" class="form-label">住所</label>
                    <input id="address"
                            type="text"
                            class="form-control form-control-lg"
                        name="address"
                            value="{{ auth()->user()->address }}">
                </div>

                <div class="mb-5">
                    <label for="building_name" class="form-label">建物名</label>
                    <input id="building_name"
                            type="text"
                            class="form-control form-control-lg"
                        name="building_name"
                            value="{{ auth()->user()->building_name }}">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-danger btn-lg py-3">
                        更新する
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('address-form');

    form.addEventListener('submit', async function(e) {
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

            if (response.ok) {
                // 成功時は購入画面に戻る
                window.location.href = '{{ route('purchases.show', $item) }}';
            } else {
                // エラーメッセージを表示
                alert(data.message || 'エラーが発生しました。');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('エラーが発生しました。');
        }
    });
});
</script>
@endpush
@endsection
