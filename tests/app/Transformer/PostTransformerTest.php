<?php

namespace Tests\App\Transformer;

use TestCase;
use App\Post;
use App\Transformer\PostTransformer;
use League\Fractal\TransformerAbstract;
use Laravel\Lumen\Testing\DatabaseMigrations;

class PostTransformerTest extends TestCase{
    use DatabaseMigrations;

    /** @test **/
    public function it_can_be_initialized() {
        $subject = new PostTransformer();
        $this->assertInstanceOf(TransformerAbstract::class, $subject);
    }

}

