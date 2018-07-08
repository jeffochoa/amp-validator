<?php

namespace App;

class ValidationError
{
    private $data;
    public $error;
    public $line;
    public $col;
    public $code;
    public $help;
    
    public function __construct(array $data)
    {
        $this->setAttributes($data);
    }

    public function setAttributes($data) : void
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function toArray() : array
    {
        return [
            'error' => $this->error,
            'line' => $this->line,
            'col' => $this->col,
            'code' => $this->code,
            'help' => $this->help
        ];
    }
}
