<?php

namespace Tests\App\Http\Controllers;

use Laravel\Lumen\Testing\DatabaseMigrations;
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
        $posts = factory('App\Post', 2)->create();
        $this->get('/posts');
        $expected = [
            'data' => $posts->toArray()
        ];
        $this->seeJsonEquals($expected);
    }

    /** @test * */
    public function show_should_return_a_valid_post()
    {
        $post = factory('App\Post')->create();
        $expected = [
            'data' => $post->toArray()
        ];
        $this
            ->get("/posts/{$post->id}")
            ->seeStatusCode(200)
            ->seeJsonEquals($expected);
    }

    /** @test * */
    public function show_should_fail_when_post_id_does_not_exist()
    {
        $this->get('/posts/9999', ['Accept' => 'application/json'])
            ->seeStatusCode(404)
            ->seeJson([
                'message' => 'Not Found',
                'status' => 404,
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

        $body = json_decode($this->response->getContent(), true);
        $this->assertArrayHasKey('data', $body);

        $data = $body['data'];

        $this->assertEquals('Third post', $data['title']);
        $this->assertEquals('Day la bai viet so 3', $data['content']);
        $this->assertTrue($data['id'] > 0, 'Expected a positive integer, but did not see one.');
        $this->seeInDatabase('posts', ['title' => 'Third post']);
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

    /** @test */
    public function update_should_only_change_fillable_fields(){
        $post =factory('App\Post')->create([
            'title' => 'ABCD Post',
            'content' => 'cndmcndmnjk ,c, lk kgjf c,v,osd,vlc dlgkslkg vx,cmv,',
            'user_id' => 1,
            'category_id' => 1,
        ]);
        $this->notSeeInDatabase('posts', [
            'title' => 'The ABCD Post'
        ]);

        $this->put("/posts/{$post->id}", [
            'title' => 'The ABCD Post',
        ]);

        $this
            ->seeStatusCode(200)
            ->seeJson([
                'title' => 'The ABCD Post',
            ])
            ->seeInDatabase('posts', [
                'title' => 'The ABCD Post',
            ]);
        $body = json_decode($this->response->getContent(), true);
        $this->assertArrayHasKey('data', $body);
    }

    /** @test */
    public function update_should_fail_with_a_invalid_id(){
        $this->put('/posts/999999', [
            'title' => 'The first post',
        ]);
        $this
            ->seeStatusCode(404)
            ->seeJsonEquals([
                'error' => [
                    'message' => 'Post not found'
                ]
            ]);
    }

    /** @test */
    public function update_should_not_match_an_invalid_route(){
        $this->put('/posts/this-is-invalid')
          ->seeStatusCode(404);
    }

    /** @test */
    public function destroy_should_remove_an_invalid_id(){
        $post = factory('App\Post')->create();
        $this->delete("/posts/{$post->id}");
        $this->seeStatusCode(204)
            ->isEmpty();
        $this->notSeeInDatabase('posts',[
            'id' => $post->id,
        ]);
    }

    /** @test */
    public function destroy_should_return_404_with_an_invalid_id(){
        $this->delete('/posts/99999');
        $this->seeStatusCode(404)
            ->seeJsonEquals([
                'error' => [
                    'message' => 'Post not found'
                ]
            ]);
    }

    /** @test */
    public function destroy_should_not_match_an_invalid_route(){
        $this->delete('/posts/this-is-invalid')
            ->seeStatusCode(404);
    }

}
