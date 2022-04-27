<?php

namespace App\Form\GestionAvis;

use App\Entity\AvisConseils\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('objet', TextType::class, [
                'label' => 'Objet',
                'empty_data' => '',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('renseignement', TextareaType::class, [
                'label' => 'Renseignement',
                'empty_data' => '',
                'required' => false,
                'attr' => [
                    'id' => 'summernote',
                    'class' => 'form-control',
                    'rows' => 10,
                    'style' => "resize: none;"
                ]
            ])
            ->add('niveauExecution', TextType::class, [
                'label' => 'Niveau d\'exécution',
                'empty_data' => '',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('documents', FileType::class, [
                'label' => 'Joindre des fichiers à la demande',
                'data_class'=>null,

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'multiple' => true,
                'constraints' => [
                    new All(
                        new File([
                            'maxSize' => '200M'
                        ])
                    )
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}
