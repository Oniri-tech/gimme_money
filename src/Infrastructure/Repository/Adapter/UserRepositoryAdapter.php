<?php

namespace App\Infrastructure\Repository\Adapter;

use App\Domain\Entity\GroupDomain;
use App\Domain\Entity\UserDomain;
use App\Domain\Repository\IUserRepository;
use App\Infrastructure\Entity\Group;
use App\Infrastructure\Entity\User;
use App\Infrastructure\Repository\GroupRepository;
use App\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserRepositoryAdapter implements IUserRepository {

    private $repo;
    private $groupRepo;
    private $em;
    public function __construct(UserRepository $repo, GroupRepositoryAdapter $groupRepositoryAdapter, EntityManagerInterface $em) {
        $this->repo = $repo;
        $this->groupRepo = $groupRepositoryAdapter;
        $this->em = $em;
    }

    public function findAll(): Array
    {
        return array_map(
            fn(User $item) =>
                new UserDomain($item->getName(), $item->getGroups()->getValues(), $item->getLend()->getValues(), $item->getBorrowing()->getValues()), $this->repo->findAll()
        );
    }

    public function findOneByName(string $name) : UserDomain
    {
        $user = $this->repo->findOneBy(['name' => $name]);
        return new UserDomain($user->getName(), $this->groupRepo->findByPeople($user) , $user->getLend()->getValues(), $user->getBorrowing()->getValues());
    }

    public function add(UserDomain $user): void
    {
        $userDoc = $this->repo->findOneBy(['name' => $user->getName()]);
        if (!$userDoc) {
            $userDoc = new User();
        }
        $userDoc->fromUserDomain($user);

        $this->em->persist($userDoc);
        $this->em->flush();
    }

    public function deleteUser(UserDomain $user): void
    {
        $userDoc = $this->repo->findOneBy(['name' => $user->getName()]);
        $this->em->remove($userDoc);
        $this->em->flush();
    }


}