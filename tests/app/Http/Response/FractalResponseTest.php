<?php

namespace Tests\App\Http\Response;

use League\Fractal\Scope;
use League\Fractal\TransformerAbstract;
use Illuminate\Http\Request;
use TestCase;
use App\Http\Response\FractalResponse;
use League\Fractal\Manager;
use League\Fractal\Serializer\SerializerAbstract;
use Mockery as m;

class FractalResponseTest extends TestCase{
    /** @test */
    public function it_can_be_initialize(){
        $manager = m::mock(Manager::class);
        $serializer = m::mock(SerializerAbstract::class);
        $request = m::mock(Request::class);
        $manager
            ->shouldReceive('setSerializer')
            ->with($serializer)
            ->once()
            ->andReturn($manager);

        $fractal = new FractalResponse($manager, $serializer, $request);
        $this->assertInstanceOf(FractalResponse::class, $fractal);
    }

    /** @test */
    public function it_can_transform_an_items(){
        $transformer = m::mock(TransformerAbstract::class);
        $request = m::mock(Request::class);
        $scope = m::mock(Scope::class);
        $scope
            ->shouldReceive('toArray')
            ->once()
            ->andReturn(['foo' => 'bar']);

        $serialize = m::mock(SerializerAbstract::class);

        $manager = m::mock('League\Fractal\Manager');
        $manager
            ->shouldReceive('setSerializer')
            ->with($serialize)
            ->once();

        $manager
            ->shouldReceive('createData')
            ->once()
            ->andReturn($scope);

        $subject = new FractalResponse($manager, $serialize, $request);

        $this->assertInternalType(
            'array',
            $subject->item(['foo' => 'bar'], $transformer)
        );
    }

    /** @test */
    public function it_can_transform_a_collection(){
        $data = [
            ['foo' => 'bar'],
            ['fizz' => 'buzz'],
        ];
        $request = m::mock(Request::class);
        $transform = m::mock(TransformerAbstract::class);

        $scope = m::mock(Scope::class);
        $scope
            ->shouldReceive('toArray')
            ->once()
            ->andReturn($data);

        $serializer = m::mock(SerializerAbstract::class);

        $manager = m::mock(Manager::class);
        $manager
            ->shouldReceive('setSerializer')
            ->once()
            ->andReturn($serializer);

        $manager
            ->shouldReceive('createData')
            ->once()
            ->andReturn($scope);

        $subject = new FractalResponse($manager, $serializer, $request);

        $this->assertInternalType(
            'array',
            $subject->collection($data, $transform)
        );
    }

    /** @test */
    public function it_should_parse_passed_includes_when_passed(){
        $serializer = m::mock(SerializerAbstract::class);
        $manager = m::mock(Manager::class);
        $manager->shouldReceive('setSerializer')->with($serializer);
        $manager
            ->shouldReceive('parseIncludes')
            ->with('posts');
        $request = m::mock(Request::class);
        $request->shouldReceive('query');
        $subject = new FractalResponse($manager, $serializer, $request);
        $subject->parseIncludes('posts');
    }

    /** @test */
    public function it_should_parse_request_query_includes_with_no_args(){
        $serializer = m::mock(SerializerAbstract::class);
        $manager = m::mock(Manager::class);
        $manager->shouldReceive('setSerializer')->with($serializer);
        $manager
            ->shouldReceive('parseIncludes')
            ->with('posts');
        $request = m::mock(Request::class);
        $request->shouldReceive('query')
            ->with('include', '')
            ->andReturn('posts');

        $subject = new FractalResponse($manager, $serializer, $request);
        $subject->parseIncludes();
    }
}
