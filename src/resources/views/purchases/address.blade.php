@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mb-4">住所の変更</h2>

            <form method="POST" action="{{ route('purchases.address.store', $item) }}">
                @csrf

                <div class="mb-4">
                    <label for="postal_code" class="form-label">郵便番号</label>
                    <input id="postal_code" 
                           type="text" 
                           class="form-control form-control-lg" 
                           name="postal_code" 
                           value="{{ auth()->user()->postal_code }}"
                           placeholder="1234567">
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
                    <label for="building" class="form-label">建物名</label>
                    <input id="building" 
                           type="text" 
                           class="form-control form-control-lg" 
                           name="building" 
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
@endsection