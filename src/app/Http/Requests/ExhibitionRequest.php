<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    /**
     * リクエストの認可を判定
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * バリデーションルール
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpeg,png'],
            'category_id' => ['required', 'exists:categories,id'],
            'condition' => ['required', 'string', 'in:new,like_new,good,fair,poor'],
            'price' => ['required', 'integer', 'min:0'],
        ];
    }

    /**
     * バリデーションメッセージ
     */
    public function messages(): array
    {
        return [
            'name.required' => '商品名を入力してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '商品説明は255文字以内で入力してください',
            'image.required' => '商品画像を選択してください',
            'image.image' => '商品画像は画像ファイルを選択してください',
            'image.mimes' => '商品画像はjpegまたはpng形式のファイルを選択してください',
            'category_id.required' => 'カテゴリーを選択してください',
            'category_id.exists' => '正しいカテゴリーを選択してください',
            'condition.required' => '商品の状態を選択してください',
            'condition.in' => '正しい商品の状態を選択してください',
            'price.required' => '商品価格を入力してください',
            'price.integer' => '商品価格は整数で入力してください',
            'price.min' => '商品価格は0円以上で入力してください',
        ];
    }

    /**
     * 商品の状態の定義
     */
    public static function conditions(): array
    {
        return [
            'new' => '新品、未使用',
            'like_new' => '未使用に近い',
            'good' => '目立った傷や汚れなし',
            'fair' => 'やや傷や汚れあり',
            'poor' => '傷や汚れあり',
        ];
    }
} 