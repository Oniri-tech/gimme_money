<?php

namespace App\Controller;

use App\Domain\Service\GroupService;
use App\Infrastructure\Group;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class GroupController extends AbstractController
{
    /**
     * @Route("/group", name="get_group", methods={"GET"})
     */
    public function getGroups(GroupService $groupService)
    {
        \dd($groupService->listAllGroups());
    }
   
    /**
     * @Route("/group", name="create_group", methods={"POST"})
     */
    public function createGroup(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager)
    {
        $jsonRecu = $request->getContent();   

        $post = $serializer->deserialize($jsonRecu, Group::class, 'json');

        $manager->persist($post);
        $manager->flush();

        dd($post);
    }

    
    /**
     * @Route("/group/{id}", name="get_group_by_id", methods={"GET"})
     */
    public function getGroupById(GroupService $groupService, $id)
    {
        \dd($groupService->getOneById($id));

    }
    /**
     * @Route("/group/{id}", name="delete_group", methods={"DELETE"})
     */
    public function deleteGroup(Request $request)
    {
        $group = $groupRepository->findOneBy(['id' => $id]);
        $manager->remove($group);
        $manager->flush();

        $response = new Response("groupe supprimé", 200, []);

        return $response;
    }
}
