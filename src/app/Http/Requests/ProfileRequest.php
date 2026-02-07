<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;

class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:20'],
            'post_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address' => ['required'],
            'icon' => ['nullable', 'image', 'mimes:jpeg,png'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'お名前を入力してください',
            'name.max' => 'お名前は20文字以内で入力してください',
            'post_code.required' => '郵便番号を入力してください',
            'post_code.regex' => '郵便番号はハイフンありの8文字で入力してください',
            'address.required' => '住所を入力してください',
            'icon.image' => '画像ファイルを選択してください',
            'icon.mimes' => '画像は.jpegまたは.pngを指定してください',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->hasFile('icon')) {
            // ① 以前の一時画像があれば削除
            if (session()->has('tmp_icon_path')) {
                Storage::disk('public')->delete(session('tmp_icon_path'));
            }

            // ② 新しい画像を一時保存
            $tmpPath = $this->file('icon')->store('tmp', 'public');

            // ③ session に保存
            session(['tmp_icon_path' => $tmpPath]);
        }
    }
}
