<?php

namespace VE\Electro\Presenters;

abstract class Presenter {

    /**
    * @var mixed
    */
    protected $entity;

    protected $hash;

    /**
    * @param $entity
    */
    function __construct($entity)
    {
        $this->entity = $entity;
    }

    public function hash()
    {
        if (! $this->hash) {
            $this->hash = uniqid();
        }

        return $this->hash;
    }

    /**
    * Allow for property-style retrieval
    *
    * @param $property
    * @return mixed
    */
    public function __get($property)
    {
        if (method_exists($this, $property))
        {
            return $this->{$property}();
        }

        return $this->entity->{$property};
    }
}
