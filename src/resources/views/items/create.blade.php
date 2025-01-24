@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">商品の出品</h2>

    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data" novalidate>
                @csrf

                <!-- 商品画像 -->
                <div class="mb-5">
                    <h3 class="section-title mb-3">商品画像</h3>
                    <div class="image-upload-area border rounded p-4 text-center">
                        <input type="file" name="image" id="image" class="d-none" accept="image/*" required>
                        <div id="upload-button-area">
                            <label for="image" class="btn btn-outline-danger mb-0">画像を選択する</label>
                        </div>
                        <div id="preview-area" class="mt-2 d-none">
                            <div class="image-wrapper position-relative mx-auto" style="width: min(100%, 300px); padding-top: min(100%, 300px);">
                                <img id="preview-image" src="" alt="プレビュー" class="position-absolute top-0 start-0 w-100 h-100" style="object-fit: cover;">
                            </div>
                            <div class="mt-3">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="change-image">画像を変更する</button>
                            </div>
                        </div>
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
                            @foreach($categories as $category)
                                <div class="form-check form-check-inline mb-2">
                                    <input class="form-check-input" type="checkbox"
                                        name="categories[]"
                                        value="{{ $category->id }}"
                                        id="category_{{ $category->id }}"
                                        {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label category-label" for="category_{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
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
                            <option value="good" {{ old('condition') == 'good' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                            <option value="fair" {{ old('condition') == 'fair' ? 'selected' : '' }}>やや傷や汚れあり</option>
                            <option value="poor" {{ old('condition') == 'poor' ? 'selected' : '' }}>状態が悪い</option>
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

                    <!-- ブランド名 -->
                    <div class="mb-4">
                        <label for="brand_name" class="form-label">ブランド名</label>
                        <input type="text" class="form-control" id="brand_name" name="brand_name" value="{{ old('brand_name') }}">
                        @error('brand_name')
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
                    <!-- 販売価格 -->
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
    </div>
</div>

<style>
@media (min-width: 768px) and (max-width: 850px) {
    .container {
        max-width: 720px;
    }
    .category-label {
        font-size: 0.9rem;
        padding: 0.4rem 0.8rem;
    }
}

@media (min-width: 1400px) and (max-width: 1540px) {
    .container {
        max-width: 1320px;
    }
}

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
    flex-direction: column;
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
    white-space: nowrap;
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const previewArea = document.getElementById('preview-area');
    const previewImage = document.getElementById('preview-image');
    const uploadButtonArea = document.getElementById('upload-button-area');
    const changeImageButton = document.getElementById('change-image');

    function handleImageChange(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewArea.classList.remove('d-none');
                uploadButtonArea.classList.add('d-none');
            }
            reader.readAsDataURL(file);
        } else {
            previewArea.classList.add('d-none');
            uploadButtonArea.classList.remove('d-none');
            previewImage.src = '';
        }
    }

    imageInput.addEventListener('change', handleImageChange);
    
    changeImageButton.addEventListener('click', function() {
        imageInput.click();
    });
});
</script>
@endpush
@endsection