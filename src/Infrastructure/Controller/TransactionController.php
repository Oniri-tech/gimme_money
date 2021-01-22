<?php

namespace App\Controller;

use App\Domain\Service\TransactionService;
use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    /**
     * @Route("/transa", name="get_transa", methods={"GET"})
     */
    public function getTransa(TransactionService $transactionService)
    {
        \dd($transactionService->listAllTransactions());
    }
}
