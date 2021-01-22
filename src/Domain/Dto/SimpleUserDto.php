<?php

namespace App\Domain\Dto;

class SimpleUserDto {

    private $name;
    

    public function __construct(string $name) {

        $this->name = $name;
    }
}