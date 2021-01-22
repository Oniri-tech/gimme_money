<?php

namespace App\Controller;

use App\Domain\Dto\NewUserDto;
use App\Domain\Service\UserService;
use App\Infrastructure\Entity\Transaction;
use App\Infrastructure\Entity\User;
use App\Repository\GroupRepository;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="create_user", methods={"POST"})
     */
    public function createUser(Request $request, UserService $userService)
    {
        $body = json_decode($request->getContent(), true);
        return $this->json($userService->addNewUser(new NewUserDto($body['name'], $body['groups'], $body['lend'], $body['borrowing'])));
        
    }

    /**
     * @Route("/user", name="get_user", methods={"GET"})
     */
    public function getUsers(UserService $userService)
    {
        \dd($userService->listAllUsers());
    }
    /**
     * @Route("/user/{name}", name="get_user_id", methods={"GET"})
     */
    public function getUserByName(UserService $userService, $name)
    {
        \dd($userService->getOneByName($name));       
    }

    /**
     * @Route("/user", name="delete_user", methods={"DELETE"})
     */
    public function deleteUser(Request $request, UserService $userService)
    {
        $body = \json_decode($request->getContent(), true);
        return $this->json($userService->deleteUser($body['name']));
    }
    /**
     * @Route("/user/{idu}/group/{idg}/cash/{amt}", name="put_cash", methods={"POST"})
     */
    public function putCash($idu, $idg, $amt, UserRepository $userRepository, GroupRepository $groupRepository, TransactionRepository $transactionRepository, EntityManagerInterface $em)
    {
        $user = $userRepository->findOneBy(['id' => $idu]);
        $peoples = $groupRepository->findOneBy(['id' => $idg])->getPeople();
        $amt = $amt /\count($peoples);
        foreach ($peoples as $key => $people) {
            if ($user->getId() == $people->getId()) {
                continue;
            }
            elseif ($transactionRepository->findOneBy(['creditor' => $people->getId(), 'debitor' => $idu])) {
                $transaction = $transactionRepository->findOneBy(['creditor' => $people->getId(), 'debitor' => $idu]);
                $amtTransaction = $transactionRepository->findOneBy(['creditor' => $people->getId(), 'debitor' => $idu])->getAmount();
                if ($amtTransaction > $amt) {
                    $transaction->setAmount($transaction->getAmount() - $amt);
                    $em->persist($transaction);
                }
                else {
                    $newTransaction = new Transaction;
                    $newTransaction->setCreditor($user);
                    $newTransaction->setDebitor($peoples[$key]);
                    $newTransaction->setAmount($amt - $amtTransaction);

                    $em->remove($transaction);
                    $em->persist($newTransaction);
                }
            }
            elseif ($transactionRepository->findOneBy(['creditor' => $idu, 'debitor' => $people->getId()])) {
                $transaction = $transactionRepository->findOneBy(['creditor' => $idu, 'debitor' => $people->getId()]);
                $transaction->setAmount($transaction->getAmount() + $amt);
                $em->persist($transaction);
            }
            else {
                $transaction = new Transaction;
                $transaction->setCreditor($user);
                $transaction->setDebitor($peoples[$key]);
                $transaction->setAmount($amt);

                $em->persist($transaction);
                
            }
        }
        $em->flush();
        \dd("dettes mises à jour");
    }

    /** 
     * @Route("/user/{name}/group", name="get_group", methods={"GET"})
     */
    public function getGroups(UserService $userService, $name)
    {
        \dd($userService->listUserGroups($name));
        
    }

    /** 
     * @Route("/user/{idu}/group/{idg}", name="add_group", methods={"POST"})
     */
    public function joinGroup(Request $request, UserRepository $userRepository, GroupRepository $groupRepository, EntityManagerInterface $manager, $idu, $idg)
    {
        $user = $userRepository->findOneBy(['id' => $idu]);
        $group = $groupRepository->findOneBy(['id' => $idg]);

        $user->addGroup($group);
        $manager->persist($user);
        $manager->flush();

        return $this->json($user, 200, [], ["groups" => "user:read"]);
    }

}
