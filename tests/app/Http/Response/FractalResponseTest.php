<?php

namespace Tests\App\Http\Response;

use League\Fractal\Scope;
use League\Fractal\TransformerAbstract;
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
        $manager
            ->shouldReceive('setSerializer')
            ->with($serializer)
            ->once()
            ->andReturn($manager);

        $fractal = new FractalResponse($manager, $serializer);
        $this->assertInstanceOf(FractalResponse::class, $fractal);
    }

    /** @test */
    public function it_can_transform_an_items(){
        $transformer = m::mock(TransformerAbstract::class);

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

        $subject = new FractalResponse($manager, $serialize);

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

        $subject = new FractalResponse($manager, $serializer);

        $this->assertInternalType(
            'array',
            $subject->collection($data, $transform)
        );
    }
}
