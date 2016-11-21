<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Post;
use Mockery\CountValidator\Exception;

class PostController extends Controller
{
    public function __construct()
    {
        //
    }

    /**
     * GET /posts
     *
     * @return array
     */
    public function index(){
        return Post::all();
    }

    /**
     * GET /posts/{id}
     *
     * @param $id
     * @return mixed
     */
    public function show($id){
        try {
            return Post::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "error" => [
                    "message" => "Post not found",
                ]
            ], 404);
        }
    }

    public function store(Request $request){
        $post = Post::create($request->all());

        return response()->json([
            'created' => true
        ], 201, [
            'Location' => route('posts.show', ['id' => $post->id])
        ]);
    }
}
