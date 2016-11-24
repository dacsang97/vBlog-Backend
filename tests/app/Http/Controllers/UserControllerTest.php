<?php

namespace Tests\App\Http;

use Illuminate\Http\Response;
use TestCase;

class UserControllerTest extends TestCase {
    /** @test */
    public function index_should_show_a_collection() {
        $users = factory('App\User', 3)->create();
        $this->get('/users');
        $body = json_decode($this->response->getContent(), true);
        $this->assertArrayHasKey('data', $body);
        foreach ($users as $user) {
            $this->seeJson([
                'display_name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]);
        }
    }

    /** @test */
    public function index_should_be_status_code_200() {
        $this->get('/users')
            ->seeStatusCode(200);
    }

    /** @test */
    public function should_be_return_a_valid_user(){
        $user = factory('App\User')->create();
        $this->get("users/{$user->id}", ["Accept" => "application/json"]);
        $this->seeStatusCode(200);
        $body = json_decode($this->response->getContent(), true);
        $this->assertArrayHasKey('data', $body);
        $this->seeJson([
            'display_name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ]);
    }

    /** @test */
    public function should_be_fail_with_a_invalid_user(){
        $this->get("users/9999", ["Accept" => "application/json"]);
        $this->seeStatusCode(Response::HTTP_NOT_FOUND);
        $this->seeJson([
            "message" => 'Not Found',
            "status" => 404,
        ]);
    }
}