<?php

namespace App\Domain\Service;

use App\Domain\Dto\SimpleTransactionDto;
use App\Domain\Repository\ITransactionRepository;
use TransactionDomain;

class TransactionService {

    private $repo;

    public function __construct(ITransactionRepository $iTransactionRepository) {
        $this->repo = $iTransactionRepository;
    }

    public function listAllTransactions()
    {
        return \array_map(
            fn(TransactionDomain $item) =>
                new SimpleTransactionDto($item->getCreditor()->getName(), $item->getDebitor()->getName(), $item->getAmount()), $this->repo->findAll()
        );
    }
}