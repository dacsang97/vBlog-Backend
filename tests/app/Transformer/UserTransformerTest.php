<?php

namespace Tests\App\Transformer;

use App\Transformer\UserTransformer;
use App\User;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use TestCase;

class UserTransformerTest extends TestCase {
    /** @test */
    public function it_can_be_initialize(){
        $subject = new UserTransformer();
        $this->assertInstanceOf(TransformerAbstract::class, $subject);
    }

    /** @test */
    public function it_transform_a_model() {
        $user = factory('App\User')->create();
        $subject = new UserTransformer();
        $transform = $subject->transform($user);
        $this->assertArrayHasKey('display_name', $transform);
        $this->assertArrayHasKey('email', $transform);
    }

    /** @test */
    public function it_can_transform_related_posts(){
        $post = $this->postFactory();
        $user = $post->user;
        $subject = new UserTransformer();
        $data = $subject->includePosts($user);
        $this->assertInstanceOf(Collection::class, $data);
    }
}