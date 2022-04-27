<?php

namespace App\Service;

use App\Entity\Abstracts\ProcessObj;
use App\Entity\AvisConseils\Avis;
use App\Entity\AvisConseils\DocAvisConseils;
use App\Entity\User;
use App\Repository\Abstracts\ProcessObjRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Workflow\WorkflowInterface;

class SaveProcessObj
{
    /**
     * @var WorkflowInterface
     */
    private $gestionProcessStateMachine;
    /**
     * @var ProcessObjRepository
     */
    private $processObjRepository;
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager,WorkflowInterface $gestionProcessStateMachine, ProcessObjRepository $processObjRepository)
    {
        $this->gestionProcessStateMachine = $gestionProcessStateMachine;
        $this->processObjRepository = $processObjRepository;
        $this->manager = $manager;
    }

    public function run(ProcessObj $processObj, User $user, $options = []){
        $processObj
            ->setCurrentState('en_attente_manager')
            ->setDepartementInitiateur(
                $user->getDepartement()
            )
            ->setCreatedBy($user)
        ;

        if($processObj instanceof Avis){
            if ($this->gestionProcessStateMachine->can($processObj, 'valider_avis')){

                foreach ($options['documents'] as $doc){
                    $fichier = md5(uniqid()).'.'.$doc->guessExtension();
                    $doc->move(
                        '%kernel.project_dir%/public/uploads/avis',
                        $fichier
                    );
                    $docDB = (new DocAvisConseils())
                        ->setOriginalName($doc->getClientOriginalName())
                        ->setPath($fichier)
                        ->setAvis($processObj);
                    $this->manager->persist($docDB);
                    $processObj->addDocAvisConseil($docDB);
                }
                $this->processObjRepository->add($processObj);
                $this->gestionProcessStateMachine->apply($processObj, 'valider_avis');
                $this->manager->flush();
            }
        }

        return $processObj;
    }
}