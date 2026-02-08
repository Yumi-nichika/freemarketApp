<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'payment_method' => ['required'],
            'shipping' => ['required', 'array'],
        ];
    }

    public function messages()
    {
        return [
            'payment_method.required' => '支払い方法を選択してください',
            'shipping.required' => '配送先を入力してください',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $shipping = $this->input('shipping', []);

            if (empty(trim($shipping['post_code'] ?? '')) &&empty(trim($shipping['address'] ?? ''))) {
                $validator->errors()->add(
                    'shipping',
                    '配送先を入力してください'
                );
            }
        });
    }
}
