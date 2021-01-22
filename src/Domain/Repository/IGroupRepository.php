<?php

namespace App\Domain\Repository;

interface IGroupRepository {

    /**
     * @return GroupDomain[]
     */
    public function findAll();

    /**
     * @return GroupDomain
     */
    public function findOneById(int $id);


}