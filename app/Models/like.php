<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class like extends Model
{
    // protected $fillable = ['user_id', 'post_id'];

    // public function post()
    // {
    //     return $this->belongsTo(Posts::class);
    // }

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    protected $table = 'likes';

    // الأعمدة التي يمكنك تعبئتها
    protected $fillable = ['user_id', 'post_id', 'status'];

    // الدالة التي تعيد عدد الإعجابات على المشاركة بناءً على post_id
    public static function countLikes($post_id)
    {
        return Like::where('post_id', $post_id)
                   ->where('status', 1) // لايك
                   ->count();
    }

    public static function unlike($user_id, $post_id)
    {
        return Like::where('user_id', $user_id)
                   ->where('post_id', $post_id)
                   ->delete();
    }
}
