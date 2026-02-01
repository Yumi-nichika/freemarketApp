<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_user_id',
        'purchaser_user_id',
        'item_name',
        'brand_name',
        'price',
        'condition_id',
        'detail',
        'item_path',
    ];

    //usersテーブルのデータを参照
    public function seller_user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchaser_user()
    {
        return $this->belongsTo(User::class);
    }

    //conditionsテーブルのデータを参照
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    //cateroriesテーブルとは中間テーブルで紐づく
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
