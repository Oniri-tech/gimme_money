<?php

namespace App\Domain\Dto;

class SimpleGroupDto {

    private $name;

    
    public function __construct(string $name) {

        $this->name = $name;
    }
}