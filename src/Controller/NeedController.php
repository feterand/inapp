<?php

namespace App\Controller;
use App\Entity\Need;
use App\Form\NeedFormType;
use App\Repository\NeedRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

class NeedController extends AbstractController
{
    public function __construct(
        private NeedRepository $needRepository,
        private EntityManagerInterface $entityManager,
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
    public function needs(): Response
    {
        $needs = $this->needRepository->findAll();

        return $this->render('need/need.html.twig', ['needs' => $needs]);
    }

    #[Route(path: '/need/new', name: 'app_new_need')]

    public function new(Request $request)
    {
        $form = $this->createForm(NeedFormType::class,null);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            /** @var Need $need */
            $need= $form->getData();
            $this->entityManager->persist($need);
            $this->entityManager->flush();
            $this->addFlash('success', 'Necesidad añadida');
            return $this->redirectToRoute('app_needs');
        }

        return $this->render('need/needform.html.twig', ['needForm' => $form]);

    }
    #[Route(path: '/need/edit/{id}', name: 'app_need_edit')]
    public function edit(Request $request, Need $need){

        $form = $this->createForm(NeedFormType::class,$need);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            /** @var Need $need */
            $need= $form->getData();
            $this->entityManager->persist($need);
            $this->entityManager->flush();
            $this->addFlash('success', 'Necesidad guardada');
            return $this->redirectToRoute('app_needs');
        }

        return $this->render('need/needform.html.twig', ['needForm' => $form]);
    }

    #[Route(path: '/need/delete/{id}', name: 'app_need_delete')]
    public function delete(Need $need){
        $need = $this->needRepository->delete($need);
        return $this->json(['message' => 'success', 'value' => true]);
    }
}
