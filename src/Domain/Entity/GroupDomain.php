<?php

namespace App\Domain\Entity;

use Doctrine\ORM\PersistentCollection;

class GroupDomain {

    private $id;
    private $name;
    private $people;

    public function __construct(int $id = null, string $name, Array $people) {
        $this->id= $id;
        $this->name = $name;
        $this->people = $people;
    }

    public function getId(){
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPeople()
    {
        return $this->people;
    }
    public function addPeople(UserDomain $user)
    {
        if (in_array($user, $this->people)) {
            $this->people[] = $user;
            $user->addGroup($this);
        }
        return $this;
    }
}