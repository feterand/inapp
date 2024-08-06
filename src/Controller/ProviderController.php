<?php

namespace App\Controller;
use App\Entity\Provider;
use App\Form\ProviderFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProviderRepository;

class ProviderController extends AbstractController
{
    public function __construct(
        private ProviderRepository $providerRepository,
        private EntityManagerInterface $entityManager,
    )
    {
    }
    #[Route(path: '/provider', name: 'app_providers')]
    public function providers(): Response
    {
        $providers = $this->providerRepository->findAll();

        return $this->render('provider/provider.html.twig', ['providers' => $providers]);
    }
    #[Route(path: '/provider/new', name: 'app_new_provider')]
    public function new(Request $request): Response
    {
        $form = $this->createForm(ProviderFormType::class);
        return $this->render('provider/providerform.html.twig', ['providerForm' => $form]);
    }
    #[Route(path: '/provider/edit/{id}', name: 'app_provider_edit')]
    public function edit(Request $request, Provider $provider): Response
    {
        $form= $this->createForm(ProviderFormType::class,$provider);
        return $this->render('provider/providerform.html.twig', ['providerForm' => $form]);
    }
    #[Route(path: '/provider/delete/{id}', name: 'app_provider_delete')]
    public function delete( Provider $provider): Response
    {
        $form= $this->createForm(ProviderFormType::class,$provider);
        return $this->render('provider/provider.html.twig', ['providerForm' => $form]);
    }
}
