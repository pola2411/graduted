<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Post;


class Post_likeController extends Controller
{
    public function countLikes($id)
    {
        // Find the post by its ID
        $post = Post::findOrFail($id);

        // Count the number of likes on the post
        $likeCount = $post->likes()->count();

        // Return the like count as JSON response
        return response()->json(['likes' => $likeCount]);
    }
}
