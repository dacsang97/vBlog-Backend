<?php

namespace App\Http\Response;

use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Serializer\SerializerAbstract;
use League\Fractal\TransformerAbstract;

class FractalResponse {

    private $manager;
    private $serialize;
    private $request;

    public function __construct(Manager $manager, SerializerAbstract $serializer, Request $request)
    {
        $this->manager = $manager;
        $this->serialize = $serializer;
        $this->manager->setSerializer($serializer);
        $this->request = $request;
    }

    public function parseIncludes($includes = null) {
        if (empty($includes)) {
            $includes = $this->request->query('include', '');
        }
        $this->manager->parseIncludes($includes);
    }

    public function item($data, TransformerAbstract $transformer, $resourceKey = null) {
        $resource = new Item($data, $transformer, $resourceKey);
        return $this->manager->createData($resource)->toArray();
    }

    public function collection($data, TransformerAbstract $transformerAbstract, $resourceKey = null) {
        return $this->createDataArray(
            new Collection($data, $transformerAbstract, $resourceKey)
        );
    }

    public function createDataArray(ResourceInterface $resource){
        return $this->manager->createData($resource)->toArray();
    }

}