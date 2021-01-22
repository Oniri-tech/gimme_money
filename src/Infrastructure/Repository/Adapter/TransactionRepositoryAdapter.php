<?php

namespace App\Infrastructure\Repository\Adapter;

use App\Domain\Repository\ITransactionRepository;
use App\Infrastructure\Entity\Transaction;
use App\Infrastructure\Repository\TransactionRepository;
use TransactionDomain;

class TransactionRepositoryAdapter implements ITransactionRepository
{
    private $repo;

    public function __construct(TransactionRepository $transactionRepository) {
        $this->repo = $transactionRepository;
    }

    public function findAll()
    {
        return \array_map(
            fn(Transaction $item) =>
                new TransactionDomain($item->getId(), $item->getCreditor(), $item->getDebitor(), $item->getAmount()), $this->repo->findAll()
        );
    }

    public function findOneById($id)
    {
        $transa = $this->repo->findOneBy(['id' => $id]);
        return new TransactionDomain($transa->getId(), $transa->getCreditor(), $transa->getDebitor(), $transa->getAmount());
    }
}