<?php

namespace App\Domain\Repository;

interface ITransactionRepository {

    /**
     * @return TransactionDomain[]
     */
    public function findAll();

    /**
     * @return TransactionDomain
     */
    public function findOneById(int $id);


}