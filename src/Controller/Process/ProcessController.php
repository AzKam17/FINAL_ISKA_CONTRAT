<?php

namespace App\Controller\Process;

use App\Entity\Abstracts\ProcessObj;
use App\Entity\AvisConseils\Avis;
use App\Entity\User;
use App\Form\GestionAvis\AvisType;
use App\Service\CreatedMailProcess;
use App\Service\SaveProcessObj;
use App\Service\ValidationManagerProcess;
use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\WorkflowInterface;

/**
 * @Route("/apps/process")
 */
class ProcessController extends AbstractController
{
    private const START_WORKFLOW_STATE = 'en_attente_manager';

    private const PROCESS_CLASSES = [
        'avis' => [
            'slug' => 'avis',
            'obj' => 'App\Entity\AvisConseils\Avis',
            'new' => AvisType::class,
            'show' => 4,
            'edit' => 3,
        ]
    ];

    /**
     * @var WorkflowInterface
     */
    private $gestionProcessStateMachine;
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(WorkflowInterface $gestionProcessStateMachine, SessionInterface $session)
    {
        $this->gestionProcessStateMachine = $gestionProcessStateMachine;
        $this->session = $session;
    }

    /**
     * Page d'accueil pour tout les process
     * @Route("/{processObj}", name="apps_process_home", methods={"GET"})
     */
    public function home($processObj){
        $perms = $this->setPermsSession($this->session);
        $this->checkProcessObjStr($processObj);

        $_els = [
            'page_title' => $processObj . '_title'
        ];

        return $this->render('apps/gestion_process/index.html.twig', array_merge($_els, [
            'processObj' => $processObj,
            'perms' => $perms,
            'status' => $perms
        ]));
    }

    /**
     * @Route("/{processObj}/new", name="", methods={"GET", "POST"})
     */
    public function new($processObj, \Symfony\Component\HttpFoundation\Request $request, SaveProcessObj $saveProcessObj){
        $this->checkProcessObjStr($processObj);
        $className = self::PROCESS_CLASSES[$processObj]['obj'];
        $classe = new $className;

        $form = $this->createForm(self::PROCESS_CLASSES[$processObj]['new'], $classe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            /* @var User $user */
            $user = $this->getUser();

            dump($classe);
            dump($saveProcessObj->run($classe, $user, [
                'documents' => $form->get('documents')->getData()
            ]));
        }

        return $this->render('apps/gestion_process/new.html.twig', [
            'form' => $form->createView(),
            'processObj' => $processObj
        ]);
    }

    /**
     * Création de l'objet process
     * @Route("/{processObj}/{perms}", name="apps_process_home_perms", methods={"GET"})
     */
    public function home_perms($processObj, $perms){
        $this->checkProcessObjStr($processObj);
        $_els = [
            'page_title' => $processObj . '_title'
        ];
        return $this->render('apps/gestion_process/index.html.twig', array_merge($_els, [
            'processObj' => $processObj,
            'perms' => $this->setPermsSession($this->session),
            'status' => $perms
        ]));
    }

    /**
     * @Route("/dep_manager/{id}", name="apps_process_dep_manager")
     */
    public function dep_manager(ProcessObj $processObj, ValidationManagerProcess $validationManagerProcess){
        /* @var User $user */
        $user = $this->getUser();

        $processObj = $validationManagerProcess->run(
            $user, $processObj, true
        );

        var_dump($processObj);

        return new Response($processObj->getObjet());
    }

    private function setPermsSession(SessionInterface $session){
        if($session->get('perms') == null){
            $perms = 'all_user';
            $perms = $this->isGranted('ROLE_USER_MANAGER') ? 'all_manager' : $perms;
            $perms = $this->isGranted('ROLE_JURIDIQUE') ? 'all_user_juridique' : $perms;
            $perms = $this->isGranted('ROLE_USER_BOSS_JURIDIQUE') ? 'all' : $perms;

            $session->set('perms', $perms);
        }
        return $session->get('perms');
    }

    private function checkProcessObjStr(string $processObj){
        if(!in_array($processObj, array_keys(self::PROCESS_CLASSES))){
            throw new \Exception("La chaine de caractère fournie n'est pas valide. Elle ne correspond à aucune classe.");
        }
    }
}