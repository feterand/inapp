<?php

namespace App\Controller;

use App\Entity\Template;
use App\Form\TemplateFormType;
use App\Repository\TemplateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TemplateController extends AbstractController
{
    public function __construct(
        private TemplateRepository $templateRepository,
        private EntityManagerInterface $entityManager,
    )
    {
    }
    #[Route(path: '/template', name: 'app_templates')]
    public function templates(): Response
    {
        $templates = $this->templateRepository->findAll();

        return $this->render('template/template.html.twig', ['templates' => $templates]);
    }
    #[Route(path: '/template/new', name: 'app_new_template')]
    public function new(Request $request): Response
    {
        $form = $this->createForm(TemplateFormType::class);
        return $this->render('template/templateform.html.twig', ['templateForm' => $form]);
    }
    #[Route(path: '/template/edit/{id}', name: 'app_template_edit')]
    public function edit(Request $request, template $template): Response
    {
        $form= $this->createForm(TemplateFormType::class,$template);
        return $this->render('template/templateform.html.twig', ['templateForm' => $form]);
    }
    #[Route(path: '/template/delete/{id}', name: 'app_template_delete')]
    public function delete( template $template): Response
    {
        $form= $this->createForm(TemplateFormType::class,$template);
        return $this->render('template/template.html.twig', ['templateForm' => $form]);
    }
}
