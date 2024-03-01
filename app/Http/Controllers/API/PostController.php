<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\boold_type;
use App\Http\Controllers\Controller;


class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index']]);
    }
    public function index()
    {

        // جميل
        //to get field from table separately
        //$bloodType = boold_type::where(['id'=>$request->user_id])->first();

        //to get data from table included in other data
        $posts = Post::select('posts.*', 'boold_types.name as booldType')
            ->join('boold_types', 'boold_types.id', '=', 'posts.boold_type')
            ->get();
        foreach ($posts as $index => $post) {

            $comments = Comment::where('post_id' ,$post->id)->get();

            $post->comments = $comments;
        }
        //$post=Post::all();
        return response()->json(
            [
                'success' => true,
                'message' => 'All Posts',
                'post' => $posts
            ]
        );
    }
    public function getComments()
    {

        // جميل
        //to get field from table separately
        //$bloodType = boold_type::where(['id'=>$request->user_id])->first();

        //to get data from table included in other data

        $Comments = Comment::all();
        return response()->json(
            [
                'success' => true,
                'message' => 'Comments',
                'Comments' => $Comments
            ]
        );
    }
    public function getTypes()
    {

        // جميل
        //to get field from table separately
        //$bloodType = boold_type::where(['id'=>$request->user_id])->first();

        //to get data from table included in other data

        $Types = boold_type::all();
        return response()->json(
            [
                'success' => true,
                'message' => 'All Types',
                'types' => $Types
            ]
        );
    }
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'description' => 'required',
            'longtitude' => 'required',
            'latitude' => 'required',
            'background' => 'required',
            'boold_type' => 'required'

        ]);

        //

        if ($validator->fails()) {
            return response()->json(
                [
                    'fails' => false,
                    'message' => 'not stored',
                    'post' => $validator->errors(),
                ]
            );
        }
        $user = JWTAuth::parseToken()->authenticate();

        // Assign user_id to the input data
        $input['user_id'] = $user->id;
        $post = Post::create($input);

        return response()->json(
            [
                'success' => true,
                'message' => 'Created Successfully',
                'post' => $post,
            ]
        );
    }
    public function storeComment(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'comment' => 'required',

        ]);

        //

        if ($validator->fails()) {
            return response()->json(
                [
                    'fails' => false,
                    'message' => 'not stored',
                    'post' => $validator->errors(),
                ]
            );
        }
        $user = JWTAuth::parseToken()->authenticate();

        // Assign user_id to the input data
        $input['user_id'] = $user->id;
        $input['post_id'] = $id;
        $comments = Comment::create($input);

        return response()->json(
            [
                'success' => true,
                'message' => 'Created Successfully',
                'post' => $comments,
            ]
        );
    }
    public function storebooldType(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',

        ]);

        //

        if ($validator->fails()) {
            return response()->json(
                [
                    'fails' => false,
                    'message' => 'not stored',
                    'post' => $validator->errors(),
                ]
            );
        }


        // Assign user_id to the input data

        $booldType = boold_type::create($input);

        return response()->json(
            [
                'success' => true,
                'message' => 'Created Successfully',
                'booldType' => $booldType,
            ]
        );
    }
    public function show($id)
    {
        //where 's our view
        // is here?


        $post = Post::find($id);

        if (is_null($post)) {
            return response()->json(
                [
                    'fails' => false,
                    'message' => 'not found',

                ]
            );
        }

        return response()->json(
            [
                'success' => true,
                'message' => 'Retrived Successfully',
                'post' => $post,
            ]
        );
    }
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $post = Post::find($id);
        $validator = Validator::make($input, [
            'description' => 'required',
            'longtitude' => 'required',
            'latitude' => 'required',
            'background' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    'fails' => false,
                    'message' => 'not updated',
                    'post' => $validator->errors(),
                ]
            );
        }
        $post->description = $input['description'];
        $post->longtitude = $input['longtitude'];
        $post->latitude = $input['latitude'];
        $post->background = $input['background'];
        $post->save();


        return response()->json(
            [
                'success' => true,
                'message' => 'updated Successfully',
                'post' => $post,
            ]
        );
    }
    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();
        return response()->json(
            [
                'success' => true,
                'message' => 'Deleted Successfully',
                'post' => $post,
            ]
        );
    }
}
