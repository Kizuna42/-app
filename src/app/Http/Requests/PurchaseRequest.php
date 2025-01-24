<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'payment_method' => ['required', 'string', 'in:credit_card,bank_transfer,convenience_store'],
            'delivery_address_id' => ['required', 'exists:addresses,id'],
        ];
    }

    /**
     * バリデーションメッセージ
     */
    public function messages(): array
    {
        return [
            'payment_method.required' => '支払い方法を選択してください',
            'payment_method.in' => '正しい支払い方法を選択してください',
            'delivery_address_id.required' => '配送先を選択してください',
            'delivery_address_id.exists' => '正しい配送先を選択してください',
        ];
    }
}