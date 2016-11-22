<?php

namespace App\Transformer;

use App\Post;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract{
    public function transform(Post $post){
        return [
            'id' => $post->id,
            'title' => $post->title,
            'content' => $post->content,
            'user_id' => $post->user_id,
            'category_id' => $post->category_id,
            'created' => $post->created_at->toIso8601String(),
            'updated' => $post->updated_at->toIso8601String(),
            'released' => $post->created_at->diffForHumans(),
        ];
    }
}