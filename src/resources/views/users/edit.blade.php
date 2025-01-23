@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mb-4">プロフィール設定</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('users.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- プロフィール画像 -->
                <div class="mb-4 d-flex align-items-center">
                    <div class="position-relative d-inline-block">
                        @if($user->avatar)
                            <img src="{{ asset('storage/avatars/' . $user->avatar) }}" alt="プロフィール画像" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="bg-secondary rounded-circle mb-3" style="width: 150px; height: 150px;"></div>
                        @endif
                    </div>
                    <div class="ms-5"> <!-- ml-4からms-5に変更してマージンを増やす -->
                        <label for="avatar" class="btn btn-outline-danger mb-0">
                            画像を選択する
                        </label>
                        <input type="file" id="avatar" name="avatar" class="d-none" accept="image/*">
                    </div>
                    @error('avatar')
                        <span class="text-danger d-block">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">ユーザー名</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="postal_code" class="form-label">郵便番号</label>
                    <input id="postal_code" type="text" class="form-control @error('postal_code') is-invalid @enderror"
                        name="postal_code"
                        value="{{ old('postal_code', $user->postal_code) }}"
                        placeholder="1234567"
                        required>
                    @error('postal_code')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">住所</label>
                    <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address', $user->address) }}" required>
                    @error('address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="building_name" class="form-label">建物名</label>
                    <input id="building_name" type="text" class="form-control @error('building_name') is-invalid @enderror" name="building_name" value="{{ old('building_name', $user->building_name) }}">
                    @error('building_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-danger">
                        更新する
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('avatar').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const container = document.querySelector('.position-relative');
            const existingImg = container.querySelector('.rounded-circle');
            const existingDiv = container.querySelector('.bg-secondary');

            const newImg = document.createElement('img');
            newImg.src = e.target.result;
            newImg.alt = "プロフィール画像";
            newImg.classList.add('rounded-circle', 'mb-3');
            newImg.style.width = '150px';
            newImg.style.height = '150px';
            newImg.style.objectFit = 'cover';

            if (existingImg) {
                existingImg.replaceWith(newImg);
            } else if (existingDiv) {
                existingDiv.replaceWith(newImg);
            }
        }
        reader.readAsDataURL(e.target.files[0]);
    }
});
</script>
@endpush
@endsection