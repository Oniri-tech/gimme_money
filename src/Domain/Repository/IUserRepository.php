<?php

namespace App\Domain\Repository;

use App\Domain\Entity\UserDomain;

interface IUserRepository {

    /**
     * @return UserDomain[]
     */
    public function findAll();

    /**
     * @return UserDomain
     */
    public function findOneByName(string $name);
    public function add(UserDomain $user): void;
    public function deleteUser(UserDomain $user): void;


}