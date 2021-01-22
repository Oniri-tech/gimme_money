<?php

namespace App\Domain\Service;

use App\Domain\Dto\SimpleGroupDto;
use App\Domain\Entity\GroupDomain;
use App\Domain\Repository\IGroupRepository;

class GroupService {
    private $repo;

    public function __construct(IGroupRepository $iGroupRepository) {
        $this->repo = $iGroupRepository;
    }

    public function listAllGroups(): array
    {
        return \array_map(
            fn(GroupDomain $item) =>
                new SimpleGroupDto($item->getName()), $this->repo->findAll()
        );
    }

    public function getOneById(int $id): SimpleGroupDto
    {
        $group = $this->repo->findOneById($id);
        return new SimpleGroupDto($group->getId(), $group->getName());
    }
}