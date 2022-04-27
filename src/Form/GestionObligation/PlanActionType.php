<?php

namespace App\Form\GestionObligation;

use App\Entity\Obligation\PlanAction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlanActionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('action', TextareaType::class, [
                'label' => 'Action',
                'empty_data' => '',
                'required' => false,
                'attr' => [
                    'class' => 'form-control customAction',
                ]
            ])
            ->add('resultatAttendu', TextareaType::class, [
                'label' => 'Résultat Attendu',
                'empty_data' => '',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('porteur', TextType::class, [
                'label' => 'Porteur',
                'empty_data' => '',
                'required' => false,
                'attr' => [
                    'class' => 'form-control customAction',
                ]
            ])
            ->add('statutAction', ChoiceType::class, [
                'label' => 'Statut',
                'empty_data' => '',
                'choices' => ['En cours', 'Terminé'],
                'choice_label'=> function($choice, $key, $value){
                    return $value;
                },
                'attr' => [
                    'class' => 'form-control customAction'
                ]
            ])
            ->add('delai', DateType::class, [
                'label' => 'Délai',
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control customAction',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PlanAction::class,
        ]);
    }
}