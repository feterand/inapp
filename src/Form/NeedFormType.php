<?php

namespace App\Form;

use App\Entity\Need;
use App\Entity\Template;
use App\Form\ProviderFormType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NeedFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class , ['label'=> "Nombre"])
            ->add('need', TextType::class , ['label'=>"Necesidad"])
            ->add('categorie', TextType::class , ['label'=>"Categoria"])
            ->add('state')
            ->add('quantity')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('template', EntityType::class, [
                'class' => Template::class,
                'choice_label' => 'name',
            ])
            ->add('provider', CollectionType::class, [
                'entry_type' => ProviderFormType::class,
                'entry_options' => ['label'=>false],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Need::class,
        ]);
    }
}
