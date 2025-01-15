@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">商品の出品</h2>

    <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data" novalidate>
        @csrf

        <!-- 商品画像 -->
        <div class="mb-5">
            <h3 class="section-title mb-3">商品画像</h3>
            <div class="image-upload-area border rounded p-4 text-center">
                <input type="file" name="image" id="image" class="d-none" accept="image/*" required>
                <label for="image" class="btn btn-outline-danger mb-0">画像を選択する</label>
                @error('image')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- 商品の詳細 -->
        <div class="mb-5">
            <h3 class="section-title mb-3">商品の詳細</h3>

            <!-- カテゴリー -->
            <div class="mb-4">
                <label class="form-label required">カテゴリー</label>
                <div class="category-buttons">
                    @foreach(['ファッション', '家電', 'インテリア', 'レディース', 'メンズ', 'コスメ', '本', 'ゲーム', 'スポーツ', 'キッチン', 'ハンドメイド', 'アクセサリー', 'おもちゃ', 'ベビー・キッズ'] as $category)
                        <div class="form-check form-check-inline mb-2">
                            <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category }}" id="category_{{ $loop->index }}">
                            <label class="form-check-label category-label" for="category_{{ $loop->index }}">{{ $category }}</label>
                        </div>
                    @endforeach
                </div>
                @error('categories')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- 商品の状態 -->
            <div class="mb-4">
                <label class="form-label required">商品の状態</label>
                <select class="form-select" name="condition" required>
                    <option value="" selected disabled>選択してください</option>
                    <option value="good">目立った傷や汚れなし</option>
                    <option value="fair">やや傷や汚れあり</option>
                    <option value="poor">状態が悪い</option>
                </select>
                @error('condition')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- 商品名と説明 -->
        <div class="mb-5">
            <h3 class="section-title mb-3">商品名と説明</h3>

            <div class="mb-4">
                <label for="name" class="form-label required">商品名</label>
                <input type="text" class="form-control" id="name" name="name" required value="{{ old('name') }}">
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="form-label required">商品の説明</label>
                <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- 販売価格 -->
        <div class="mb-5">
            <h3 class="section-title mb-3">販売価格</h3>
            <div class="mb-4">
                <label for="price" class="form-label required">価格</label>
                <div class="input-group">
                    <input type="number" class="form-control" id="price" name="price" required value="{{ old('price') }}" placeholder="¥" min="1">
                </div>
                @error('price')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-danger btn-lg px-5">出品する</button>
        </div>
    </form>
</div>

<style>
.section-title {
    font-size: 1.2rem;
    font-weight: bold;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #dee2e6;
}

.required::after {
    content: " *";
    color: #ff4d4d;
}

.image-upload-area {
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
}

.category-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.category-label {
    padding: 0.5rem 1rem;
    border: 1px solid #ff4d4d;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.2s;
    color: #ff4d4d;
}

.category-label:hover {
    background-color: rgba(255, 77, 77, 0.1);
}

.form-check-input:checked + .category-label {
    background-color: #ff4d4d;
    color: white;
    border-color: #ff4d4d;
}

.form-check-input {
    display: none;
}
</style>
@endsection