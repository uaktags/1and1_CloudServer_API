<?php

namespace NGCSv1\Entity;


class Region extends AbstractEntity
{
    public $id;
    public $name;

    /**
     * @param \stdClass|array $parameters
     */
    public function build($parameters)
    {
        foreach ($parameters as $property => $value) {
            die(var_dump($parameters));
            switch ($property) {
                default:
                    $this->{\NGCSv1\convert_to_camel_case($property)} = $value;
                    break;
            }
        }
    }
}