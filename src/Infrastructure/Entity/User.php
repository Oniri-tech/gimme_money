<?php

namespace App\Infrastructure\Entity;

use App\Domain\Entity\UserDomain;
use Doctrine\ORM\Mapping as ORM;
use App\Infrastructure\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"group:read", "user:read", "user:debiteur"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"group:read", "user:read", "user:debiteur", "transa:read"})
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Group::class, mappedBy="people")
     * @Groups("user:read")
     */
    private $groups;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="creditor", orphanRemoval=true)
     */
    private $lend;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="debitor", orphanRemoval=true)
     */
    private $borrowing;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
        $this->lend = new ArrayCollection();
        $this->borrowing = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->addPerson($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->removeElement($group)) {
            $group->removePerson($this);
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getLend(): Collection
    {
        return $this->lend;
    }

    public function addLend(Transaction $lend): self
    {
        if (!$this->lend->contains($lend)) {
            $this->lend[] = $lend;
            $lend->setCreditor($this);
        }

        return $this;
    }

    public function removeLend(Transaction $lend): self
    {
        if ($this->lend->removeElement($lend)) {
            // set the owning side to null (unless already changed)
            if ($lend->getCreditor() === $this) {
                $lend->setCreditor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getBorrowing(): Collection
    {
        return $this->borrowing;
    }

    public function addBorrowing(Transaction $borrowing): self
    {
        if (!$this->borrowing->contains($borrowing)) {
            $this->borrowing[] = $borrowing;
            $borrowing->setDebitor($this);
        }

        return $this;
    }

    public function removeBorrowing(Transaction $borrowing): self
    {
        if ($this->borrowing->removeElement($borrowing)) {
            // set the owning side to null (unless already changed)
            if ($borrowing->getDebitor() === $this) {
                $borrowing->setDebitor(null);
            }
        }

        return $this;
    }

    public function fromUserDomain(UserDomain $userDomain)
    {
        $this->name = $userDomain->getName();
        $this->groups = $userDomain->getGroups();
        $this->lend = $userDomain->getLend();
        $this->borrowing = $userDomain->getBorrowing();
    }

}
