<?php

namespace App;

class ErrorsCollection
{
    private $items = [];

    public function __construct($errors)
    {
        $this->items = $errors;
    }

    public function all()
    {
        return $this->items;
    }

    public function toArray() : array
    {
        return array_map(function ($error) {
            return $error->toArray();
        }, $this->items);
    }
}
