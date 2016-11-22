<?php

namespace Tests\App\Transformer;

use TestCase;
use App\Post;
use App\Transformer\PostTransformer;
use League\Fractal\TransformerAbstract;
use Laravel\Lumen\Testing\DatabaseMigrations;

class PostTransformerTest extends TestCase{
    /** @test **/
    public function it_can_be_initialized() {
        $subject = new PostTransformer();
        $this->assertInstanceOf(TransformerAbstract::class, $subject);
    }

    /** @test */
    public function it_transform_a_post_model(){
        $post = factory(Post::class)->create();
        $subject = new PostTransformer();
        $transform = $subject->transform($post);
        $this->assertArrayHasKey('id', $transform);
        $this->assertArrayHasKey('title', $transform);
        $this->assertArrayHasKey('content', $transform);
        $this->assertArrayHasKey('user_id', $transform);
        $this->assertArrayHasKey('category_id', $transform);
        $this->assertArrayHasKey('created', $transform);
        $this->assertArrayHasKey('updated', $transform);
    }

}

