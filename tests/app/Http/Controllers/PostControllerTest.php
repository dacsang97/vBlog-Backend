<?php

namespace Tests\App\Http\Controllers;

use TestCase;

class PostControllerTest extends TestCase
{
    /** @test * */
    public function index_status_code_should_be_200()
    {
        $this->get('/posts')->seeStatusCode(200);
    }

    /** @test * */
    public function index_should_return_a_collection_of_records()
    {
        $this
            ->get('/posts')
            ->seeJson([
                'title' => 'First Post'
            ])
            ->seeJson([
                'title' => 'Second Post'
            ]);
    }

    /** @test * */
    public function show_should_return_a_valid_post()
    {
        $this
            ->get('/posts/1')
            ->seeStatusCode(200)
            ->seeJson([
                'id' => 1,
                'title' => 'First Post',
                'user_id' => 1,
                'category_id' => 1,
            ]);
        $data = json_decode($this->response->getContent(), true);
        $this->assertArrayHasKey('content', $data);
    }

    /** @test * */
    public function show_should_fail_when_post_id_does_not_exist()
    {
        $this->get('/posts/9999')
            ->seeStatusCode(404)
            ->seeJson([
                "error" => [
                    "message" => "Post not found",
                ]
            ]);

    }

    /** @test * */
    public function show_route_should_not_match_an_invalid_route()
    {
        $this->get('/posts/this-is-invalid');
        $this->assertNotRegExp(
            "/Post not found/",
            $this->response->getContent(),
            'PostsController@show route matching when it should not.'
        );
    }

    /** @test */
    public function store_should_save_new_post_in_database()
    {
        $this->post('/posts', [
            'title' => 'Third post',
            'content' => 'Day la bai viet so 3',
            'user_id' => 2,
            'category_id' => 3,
        ]);

        $this
            ->seeJson(['created' => true])
            ->seeInDatabase('posts', ['title' => 'Third post']);
    }

    /** @test */
    public function store_should_respond_with_a_201_and_location_header_when_successful()
    {
        $this->post('/posts', [
            'title' => 'Fourth post',
            'content' => 'Day la bai viet so 4',
            'user_id' => 2,
            'category_id' => 3,
        ]);

        $this
            ->seeStatusCode(201)
            ->seeHeaderWithRegExp('Location', '#/posts/[\d]+$#');
    }

}
