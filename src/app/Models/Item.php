<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_name',
        'brand_name',
        'price',
        'condition_id',
        'detail',
        'item_path',
    ];

    //usersテーブルのデータを参照
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //conditionsテーブルのデータを参照
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    //sold_itemsテーブルのデータを参照
    public function soldItem()
    {
        return $this->hasOne(SoldItem::class, 'item_id');
    }

    //cateroriesテーブルとは中間テーブルで紐づく
    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'category_item',
            'item_id',
            'category_id'
        );
    }
}
