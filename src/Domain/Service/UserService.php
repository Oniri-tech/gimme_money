<?php

namespace App\Domain\Service;

use App\Domain\Dto\NewUserDto;
use App\Domain\Dto\SimpleGroupDto;
use App\Domain\Dto\SimpleUserDto;
use App\Domain\Entity\GroupDomain;
use App\Infrastructure\Entity\Group;
use App\Domain\Entity\UserDomain;
use App\Domain\Repository\IGroupRepository;
use App\Domain\Repository\IUserRepository;

class UserService {

    private $repo;
    private $groupRepo;

    public function __construct(IUserRepository $repo, IGroupRepository $iGroupRepository) {
        $this->repo = $repo;
        $this->groupRepo = $iGroupRepository;

    }

    public function listAllUsers():array
    {
        return array_map(
            fn(UserDomain $item) =>
                new SimpleUserDto($item->getName()), $this->repo->findAll());
    }

    public function getOneByName(string $name): SimpleUserDto
    {
        $user = $this->repo->findOneByName($name);
        return new SimpleUserDto($user->getName());
    }

    public function listUserGroups($name)
    {
        return \array_map(
            fn(GroupDomain $item) =>
                new SimpleGroupDto($item->getName()), $this->repo->findOneByName($name)->getGroups()
        );
    }

    public function addNewUser(NewUserDto $newUserDto)
    {
        $user = new UserDomain($newUserDto->name, $newUserDto->groups, $newUserDto->lend, $newUserDto->borrowing);
        $this->repo->add($user);
        return new SimpleUserDto($user->getName());
    }

    public function joinGroup(string $name, int $idg)
    {
        $user = $this->repo->findOneByName($name);
        $groupInfra = $this->groupRepo->findOneById($idg);
        $group = new GroupDomain($groupInfra->getId(), $groupInfra->getName(), $groupInfra->getPeople());
        $user->addGroup($group);

    }

    public function deleteUser(string $name)
    {
        $user = $this->repo->findOneByName($name);
        $this->repo->deleteUser($user);

        return "utilisateur ". $user->getName() ." supprimé";
    }

}