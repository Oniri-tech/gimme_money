<?php

namespace App\Domain\Dto;

use Doctrine\ORM\PersistentCollection;

class NewUserDto {

    public $name;
    public $groups;
    public $lend;
    public $borrowing;

    public function __construct(string $name, Array $groups, Array $lend, Array $borrowing)
    {
        $this->name = $name;
        $this->groups = $groups;
        $this->lend = $lend;
        $this->borrowing = $borrowing;
    }
}