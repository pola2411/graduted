<?php

namespace App\Http\Controllers\API;
//use App\Http\Models\Posts;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class likeController extends Controller
{
    // public function like(Posts $post)
    // {
    //     $post->likes()->create(['user_id' => auth()->id()]);
    //     return response()->json(['message' => 'Post liked']);
    // }

    // public function unlike(Posts $post)
    // {
    //     $post->likes()->where('user_id', auth()->id())->delete();
    //     return response()->json(['message' => 'Post unliked']);
    // }

    // public function like(Request $request, Posts $post)
    // {
    //     // Check if the user has already liked the post
    //     if ($post->likes()->where('user_id', auth()->id())->exists()) {
    //         return response()->json(['message' => 'You have already liked this post'], 400);
    //     }

    //     // Create a new like for the post
    //     $like = new Like();
    //     $like->user_id = auth()->id();
    //     $post->likes()->save($like);

    //     return response()->json(['message' => 'Post liked successfully']);
    // }

    public function getLikesCount($post_id)
    {
        $likesCount = Like::countLikes($post_id);
        return response()->json(['likes_count' => $likesCount]);
    }

    public function unlikePost($user_id, $post_id)
    {
        Like::unlike($user_id, $post_id);
        return response()->json(['message' => 'Like removed successfully']);
    }
    // public function unlike(Request $request, Posts $post)
    // {
    //     // Find the like associated with the authenticated user and the post
    //     $like = Like::where('post_id', $post->id)
    //                 ->where('user_id', auth()->id())
    //                 ->first();

    //     if ($like) {
    //         $like->delete(); // Delete the like
    //         return response()->json(['message' => 'Post unliked successfully']);
    //     }

    //     return response()->json(['message' => 'You have not liked this post'], 400);
    // }
}
