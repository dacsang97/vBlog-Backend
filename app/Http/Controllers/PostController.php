<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use App\User;

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
        return [
            ['title' => 'First Post'],
            ['title' => 'Second Post']
        ];
    }
}
