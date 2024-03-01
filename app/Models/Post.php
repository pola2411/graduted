<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Post extends Model
{

    use HasFactory;

    public $theComments;
    protected $table = 'posts';
    protected $fillable=['description','longtitude','latitude','background','user_id', 'boold_type'];
}
