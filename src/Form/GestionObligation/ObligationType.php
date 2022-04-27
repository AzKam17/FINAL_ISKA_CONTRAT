<?php

namespace App\Form\GestionObligation;

use App\Entity\Obligation\Obligation;
use App\Repository\Obligation\ImportanceObligationRepository;
use App\Repository\Obligation\ReferenceListeRepository;
use App\Repository\Obligation\SourceListeRepository;
use App\Repository\Obligation\StatutObligationRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObligationType extends AbstractType
{
    /**
     * @var SourceListeRepository
     */
    private $sourceListeRepository;
    /**
     * @var ReferenceListeRepository
     */
    private $referenceListeRepository;
    /**
     * @var StatutObligationRepository
     */
    private $statutObligationRepository;
    /**
     * @var ImportanceObligationRepository
     */
    private $importanceObligationRepository;

    public function __construct(
        SourceListeRepository $sourceListeRepository,
        ReferenceListeRepository $referenceListeRepository,
        StatutObligationRepository $statutObligationRepository,
        ImportanceObligationRepository $importanceObligationRepository
    )
    {
        $this->sourceListeRepository = $sourceListeRepository;
        $this->referenceListeRepository = $referenceListeRepository;
        $this->statutObligationRepository = $statutObligationRepository;
        $this->importanceObligationRepository = $importanceObligationRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $opt = ['dispoDeroulante' => true];
        $sources = $this->sourceListeRepository->findBy($opt);
        $refs = $this->referenceListeRepository->findBy($opt);
        $statuts = $this->statutObligationRepository->findAll();
        $obl = $this->importanceObligationRepository->findBy($opt);

        $builder
            ->add('sourceList', ChoiceType::class, [
                'label' => 'Type de la source',
                'empty_data' => '',
                'choices' => $sources,
                'choice_label'=> function($choice, $key, $value){
                    return $choice->getLib();
                },
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('sourceDisposition', TextareaType::class, [
                'label' => 'Libellé de la source',
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('referenceListe', ChoiceType::class, [
                'label' => 'Type de la référence',
                'empty_data' => '',
                'choices' => $refs,
                'choice_label'=> function($choice, $key, $value){
                    return $choice->getLib();
                },
                'attr' => [
                    'class' => 'form-control slct-2'
                ]
            ])
            ->add('reference', TextareaType::class, [
                'label' => 'Libellé de la référence',
                'empty_data' => '',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('pointsAbordes', TextareaType::class, [
                'label' => 'Points abordés',
                'empty_data' => '',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('obligation', TextareaType::class, [
                'label' => 'Obligation',
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut de l\'obligation',
                'empty_data' => '',
                'choices' => $statuts,
                'choice_label'=> function($choice, $key, $value){
                    return $choice->getLib();
                },
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('sanctions', TextareaType::class, [
                'label' => 'Sanctions',
                'empty_data' => '',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            /*->add('prevues', TextareaType::class, [
                'label' => 'Preuves',
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'display:none;'

                ]
            ])*/
            ->add('impact', TextareaType::class, [
                'label' => 'Impact des sanctions',
                'empty_data' => '',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('importanceObligation', ChoiceType::class, [
                'label' => 'Importance de l\'obligation',
                'empty_data' => '',
                'choices' => $obl,
                'choice_label'=> function($choice, $key, $value){
                    return $choice->getLib();
                },
                'attr' => [
                    'class' => 'form-control'
                ]
            ]);
            if(!$options['show']){
                $builder
                    ->add('fichier', FileType::class, [
                        'label' => 'Joindre le fichier',
                        'mapped' => false,
                        'required' => false,
                    ])
                    ->add('preuves', CollectionType::class, [
                        "entry_type" => PreuveType::class,
                        'allow_add' => true
                    ])
                    ->add('submit', SubmitType::class, [
                        'label' => 'Enregistrer',
                        'attr'=>[
                            'class'=>'btn btn-success btn-sm'
                        ]
                ]);
            }

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Obligation::class,
            'show' => false
        ]);
    }
}
