<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'user_id',
        'comment',
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

    //user_profilesテーブルのデータを参照
    public function profile()
    {
        return $this->belongsTo(
            UserProfile::class,
            'user_id',
            'user_id'
        );
    }
}
