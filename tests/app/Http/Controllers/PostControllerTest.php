<?php

namespace Tests\App\Http\Controllers;

use TestCase;

class PostControllerTest extends TestCase
{
    /** @test **/
    public function index_status_code_should_be_200()
    {
        $this->get('/posts')->seeStatusCode(200);
    }

    /** @test **/
    public function index_should_return_a_collection_of_records(){
        $this
            ->get('/posts')
            ->seeJson([
                'title' => 'First Post'
            ])
            ->seeJson([
                'title' => 'Second Post'
            ]);
    }
}
