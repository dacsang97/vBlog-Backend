<?php

namespace App\Http\Controllers;

use App\Transformer\UserTransformer;
use App\User;

class UserController extends Controller {
    public function index(){
        return $this->collection(User::all(), new UserTransformer());
    }

    public function show($id){
        $user = User::findOrFail($id);
        return $this->item($user, new UserTransformer());
    }
}