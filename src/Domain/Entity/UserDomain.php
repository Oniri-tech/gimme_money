<?php

namespace App\Domain\Entity;


class UserDomain
{
    private $name;
    private $groups;
    private $lend;
    private $borrowing;

    public function __construct(string $name, Array $groups, Array $lend, Array $borrowing)
    {
        $this->name = $name;
        $this->groups = $groups;
        $this->lend = $lend;
        $this->borrowing = $borrowing;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getGroups()
    {
        return $this->groups;
    }
    public function getLend()
    {
        return $this->lend;
    }
    public function getBorrowing()
    {
        return $this->borrowing;
    }

    public function addGroup(GroupDomain $group)
    {
        if (!\in_array($group, $this->groups)) {
            $this->groups[] = $group;
            $group->addPeople($this);
        }
        return $this;
    }
    

}
