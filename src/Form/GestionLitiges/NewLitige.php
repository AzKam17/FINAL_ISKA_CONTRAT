<?php

namespace App\Form\GestionLitiges;

use App\Entity\GestionContentieux\Litige;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewLitige extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateCas', DateType::class, [
                'label' => "Date du litige",
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control datetimepicker-input',
                ]
            ])
            ->add('origineCas', TextType::class, [
                'label' => 'Origine du cas',
                'empty_data' => '',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('juridiction', TextType::class, [
                'label' => 'Juridiction',
                'empty_data' => '',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('nature', TextType::class, [
                'label' => 'Nature du litige',
                'empty_data' => '',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('avocat', TextType::class, [
                'label' => 'Avocat',
                'empty_data' => '',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('procedure', TextType::class, [
                'label' => 'Procedure',
                'empty_data' => '',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('partieDemandeur', TextType::class, [
                'label' => 'Partie demandeure',
                'empty_data' => '',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('partieDefendeur', TextType::class, [
                'label' => 'Partie defendeure',
                'empty_data' => '',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('fait', TextareaType::class, [
                'label' => 'Faits',
                'empty_data' => '',
                'required' => false,
                'attr' => [
                    'id' => 'summernote',
                    'class' => 'form-control',
                    'rows' => 10,
                    'style' => "resize: none;"
                ]
            ])
            ->add('avisJuridique', TextareaType::class, [
                'label' => 'Avis juridique',
                'empty_data' => '',
                'required' => false,
                'attr' => [
                    'id' => 'summernote',
                    'class' => 'form-control',
                    'rows' => 10,
                    'style' => "resize: none;"
                ]
            ])
            ->add('estimationFinanciere', TextType::class, [
                'label' => 'Conséquences financières à supporter par MOOV',
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('estimationConsequencesFinancieresProfit', TextType::class, [
                'label' => 'Estimation des conséquences financières au profit de MOOV',
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('provision', TextType::class, [
                'label' => 'Provision',
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Litige::class,
        ]);
    }
}