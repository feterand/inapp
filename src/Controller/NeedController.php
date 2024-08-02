<?php

namespace App\Controller;
use App\Entity\Need;
use App\Repository\NeedRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NeedController extends AbstractController
{
    public function __construct(
        private NeedRepository $needRepository,
        )
    {
    }
    #[Route('/temp', name: 'app_need')]
    public function index(): Response
    {
        return $this->render('need/index.html.twig', [
            'controller_name' => 'NeedController',
        ]);
    }

    #[Route(path: '/', name: 'app_needs')]
    public function users(): Response
    {
        $needs = $this->needRepository->findAll();

        return $this->render('need/need.html.twig', ['needs' => $needs]);
    }

    #[Route(path: '/need/new', name: 'app_new_need')]

    public function newUser(Request $request)
    {

    }
}
