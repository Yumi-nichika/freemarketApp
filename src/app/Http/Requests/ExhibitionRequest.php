<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;

class ExhibitionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'item_name' => ['required', 'string', 'max:255'],
            'detail' => ['required', 'string', 'max:255'],
            'item_image' => ['required','image', 'mimes:jpeg,png'],
            'categories'   => ['required', 'array'],
            'categories.*' => ['integer', 'exists:categories,id'],
            'condition_id' => ['required', 'integer', 'exists:conditions,id'],
            'price' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages()
    {
        return [
            'item_name.required' => '商品名を入力してください',
            'detail.required' => '商品の説明を入力してください',
            'detail.max' => '商品の説明は255文字以内で入力してください',
            'item_image.required' => '画像を選択してください',
            'item_image.image' => '画像ファイルを選択してください',
            'item_image.mimes' => '画像は.jpegまたは.pngを指定してください',
            'categories.required' => 'カテゴリーを選択してください',
            'categories.exists' => 'カテゴリーを選択してください',
            'condition_id.required' => '商品の状態を選択してください',
            'condition_id.exists' => '商品の状態を選択してください',
            'price.required' => '販売価格を入力してください',
            'price.integer' => '販売価格は0円以上で入力してください',
            'price.min' => '販売価格は0円以上で入力してください',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->hasFile('item_image')) {
            // ① 以前の一時画像があれば削除
            if (session()->has('tmp_item_image_path')) {
                Storage::disk('public')->delete(session('tmp_item_image_path'));
            }

            // ② 新しい画像を一時保存
            $tmpPath = $this->file('item_image')->store('tmp', 'public');

            // ③ session に保存
            session(['tmp_item_image_path' => $tmpPath]);
        }
    }
}
