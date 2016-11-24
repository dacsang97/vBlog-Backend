<?php

namespace App\Http\Controllers;

use App\Transformer\PostTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Post;
use Mockery\CountValidator\Exception;

class PostController extends Controller
{
    /**
     * GET /posts
     *
     * @return array
     */
    public function index(){
        return $this->collection(Post::all(), new PostTransformer());
    }

    /**
     * GET /posts/{id}
     *
     * @param $id
     * @return mixed
     */
    public function show($id){
        return $this->item(Post::findOrFail($id), new PostTransformer());
    }

    /**
     * POST /posts
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request){
        $post = Post::create($request->all());
        $data = $this->item($post, new PostTransformer());
        return response()->json($data, 201, [
            'Location' => route('posts.show', ['id' => $post->id])
        ]);
    }

    /**
     * PUT /posts/{id}
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id){
        try {
            $post = Post::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "error" => [
                    "message" => "Post not found",
                ]
            ], 404);
        }
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
        ]);
        $post->fill($request->all());
        $post->save();
        return $this->item($post, new PostTransformer());
    }

    /**
     * DELETE /posts/{id}
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function destroy($id){
        try {
            $post = Post::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "error" => [
                    "message" => "Post not found",
                ]
            ], 404);
        }
        $post->delete();
        return response(null, 204);
    }
}
