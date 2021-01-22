<?php

namespace App\Domain\Dto;

use App\Infrastructure\Entity\User;

class SimpleTransactionDto {

    private $creditor;
    private $debitor;
    private $amount;

    public function __construct(string $creditor, string $debitor, int $amount)
    {
        $this->creditor = $creditor;
        $this->debitor = $debitor;
        $this->amount = $amount;
    }
}