<?php

namespace App\Transformer;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract {
    protected $availableIncludes = [
        'posts'
    ];

    public function includePosts(User $user){
        return $this->collection($user->posts, new PostTransformer());
    }

    public function transform(User $user){
        return [
            'display_name' => $user->name,
            'email' => $user->email,
            'role' => $user->role_id,
        ];
    }
}