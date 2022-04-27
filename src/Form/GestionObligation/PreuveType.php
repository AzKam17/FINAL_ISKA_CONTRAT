<?php

namespace App\Form\GestionObligation;

use App\Entity\Obligation\Preuve;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PreuveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('intitule', TextType::class, [
                'label' => 'Intitulé',
                'empty_data' => '',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('fichiers', FileType::class, [
                'label' => 'Joindre le fichier',
                'mapped' => false,
                'required' => false,
            ])
            ->add('actions', CollectionType::class, [
                "entry_type" => PlanActionType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Preuve::class,
        ]);
    }
}