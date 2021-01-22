<?php

namespace App\Infrastructure\Entity;

use App\Infrastructure\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TransactionRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="lend")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"transa:read"})
     */
    private $creditor;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="borrowing")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"transa:read"})
     */
    private $debitor;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"transa:read"})
     */
    private $amount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreditor(): ?User
    {
        return $this->creditor;
    }

    public function setCreditor(?User $creditor): self
    {
        $this->creditor = $creditor;

        return $this;
    }

    public function getDebitor(): ?User
    {
        return $this->debitor;
    }

    public function setDebitor(?User $debitor): self
    {
        $this->debitor = $debitor;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
