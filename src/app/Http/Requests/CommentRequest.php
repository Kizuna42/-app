<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * リクエストの認可を判定
     */
    public function authorize(): bool
    {
        return auth()->check(); // ログインユーザーのみ許可
    }

    /**
     * バリデーションルール
     */
    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * バリデーションメッセージ
     */
    public function messages(): array
    {
        return [
            'content.required' => 'コメントを入力してください',
            'content.max' => 'コメントは255文字以内で入力してください',
        ];
    }
}