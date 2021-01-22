<?php

namespace App\Infrastructure\Repository\Adapter;

use App\Domain\Entity\GroupDomain;
use App\Domain\Repository\IGroupRepository;
use App\Infrastructure\Entity\Group;
use App\Infrastructure\Entity\User;
use App\Infrastructure\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;

class GroupRepositoryAdapter implements IGroupRepository {

    private $repo;
    private $em;

    public function __construct(GroupRepository $groupRepository, EntityManagerInterface $entityManagerInterface) {
        $this->repo = $groupRepository;
        $this->em = $entityManagerInterface;
    }

    public function findAll(): Array
    {
        return \array_map(
            fn(Group $item) =>
                new GroupDomain($item->getId(), $item->getName(), $item->getPeople()->getValues()), $this->repo->findAll()
        );
    }

    public function findOneById(int $id): GroupDomain
    {
        $group = $this->repo->findOneBy(['id' => $id]);
        return new GroupDomain($group->getId(), $group->getName(), $group->getPeople()->getValues());
    }

    public function findByPeople(User $user): Array
    {
        $groups = $this->repo->findAll();
        foreach ($groups as $key => $group) {
            if (!in_array($user ,$group->getPeople()->getValues())) {
                unset($groups[$key]);
            }
        }
        return \array_map(
            fn(Group $item) =>
                new GroupDomain($item->getId(), $item->getName(), $item->getPeople()->getValues()), $groups 
        );
    }
    public function add(GroupDomain $group): void
    {
        $groupDoc = $this->repo->findOneBy(["id" => $group->getId()]);
        if ($groupDoc) {
            $groupDoc = new Group();
        }
        $groupDoc->fromGroupDomain($group);
        $this->em->persist($groupDoc);
        $this->em->flush();

    }    
}