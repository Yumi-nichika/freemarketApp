<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'user_id',
        'payment_method',
        'post_code',
        'address',
        'building',
    ];

    //itemsテーブルのデータを参照
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    //usersテーブルのデータを参照
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
