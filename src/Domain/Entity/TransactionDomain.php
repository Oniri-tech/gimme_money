<?php

use App\Infrastructure\Entity\User;

class TransactionDomain {

    private $id;
    private $creditor;
    private $debitor;
    private $amount;

    public function __construct(int $id, User $creditor, User $debitor, int $amount) {
        $this->id = $id;
        $this->creditor = $creditor;
        $this->debitor = $debitor;
        $this->amount = $amount;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getCreditor()
    {
        return $this->creditor;
    }
    public function getDebitor()
    {
        return $this->debitor;
    }
    public function getAmount()
    {
        return $this->amount;
    }
}