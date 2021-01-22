<?php

namespace App\Infrastructure\Entity;

use App\Domain\Entity\GroupDomain;
use App\Infrastructure\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GroupRepository::class)
 * @ORM\Table(name="`group`")
 */
class Group
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"group:read", "user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"group:read", "user:read"})
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="groups")
     * @Groups({"group:read"})
     */
    private $people;

    public function __construct()
    {
        $this->people = new ArrayCollection();
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
     * @return Collection|User[]
     */
    public function getPeople(): Collection
    {
        return $this->people;
    }

    public function addPerson(User $person): self
    {
        if (!$this->people->contains($person)) {
            $this->people[] = $person;
        }

        return $this;
    }

    public function removePerson(User $person): self
    {
        $this->people->removeElement($person);

        return $this;
    }

    public function fromGroupDomain(GroupDomain $group)
    {
        $this->id = $group->getId();
        $this->name = $group->getName();
        $this->people = $group->getPeople();
    }
}
