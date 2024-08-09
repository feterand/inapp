<?php

namespace App\Controller;

use App\Entity\Template;
use App\Form\TemplateFormType;
use App\Repository\TemplateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function new(
        Request $request,
        #[Autowire('%kernel.project_dir%/public/uploads/templates')] string $templatesDirectory,
        SluggerInterface $slugger,
    ): Response
    {
        $template = new Template();
        $form = $this->createForm(TemplateFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            /**@var UploadedFile $templateFile **/
            $templateFile=$form->get('url')->getData();
            if ($templateFile) {
                $originalFilename = pathinfo($templateFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$templateFile->guessExtension();
                try {
                    $templateFile->move($templatesDirectory,$newFilename);
                } catch (FileException $e) {
                    dump($e);
                }
                $template->setUrl($templateFile);
            }
            $template->setName($form->get('name')->getData());
            $template->setCategorie($form->get('categorie')->getData());
            $this->entityManager->persist($template);
            $this->entityManager->flush();
            $this->addFlash('success', 'Plantilla añadida');
            return $this->redirectToRoute('app_templates');
        }
        return $this->render('template/templateform.html.twig', ['templateForm' => $form]);
    }
    #[Route(path: '/template/edit/{id}', name: 'app_template_edit')]
    public function edit(Request $request, template $template,
                         #[Autowire('%kernel.project_dir%/public/uploads/templates')] string $templatesDirectory,
                         SluggerInterface $slugger,
    ): Response
    {
        $form= $this->createForm(TemplateFormType::class,$template);
        if ($form->isSubmitted() && $form->isValid()) {
            /**@var UploadedFile $templateFile * */
            $templateFile = $form->get('url')->getData();
            if ($templateFile) {
                $originalFilename = pathinfo($templateFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $templateFile->guessExtension();
                try {
                    $templateFile->move($templatesDirectory, $newFilename);
                } catch (FileException $e) {
                    dump($e);
                }
                $template->setUrl($templateFile);
            }
            $template->setName($form->get('name')->getData());
            $template->setCategorie($form->get('categorie')->getData());
            $this->entityManager->persist($template);
            $this->entityManager->flush();
            $this->addFlash('success', 'Plantilla modificada');
            return $this->redirectToRoute('app_templates');
        }
        return $this->render('template/templateform.html.twig', ['templateForm' => $form]);
    }
    #[Route(path: '/template/delete/{id}', name: 'app_template_delete')]
    public function delete( template $template): Response
    {
        $form= $this->createForm(TemplateFormType::class,$template);
        return $this->render('template/template.html.twig', ['templateForm' => $form]);
    }
}
