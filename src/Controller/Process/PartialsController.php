<?php

namespace App\Controller\Process;

use App\Entity\AvisConseils\Avis;
use App\Entity\User;
use App\Repository\UserJuridiqueRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PartialsController extends AbstractController
{
    private const REPOS = [
        'avis' => Avis::class
    ];
    /**
     * @var UserJuridiqueRepository
     */
    private $userJuridiqueRepository;

    public function __construct(UserJuridiqueRepository $userJuridiqueRepository)
    {
        $this->userJuridiqueRepository = $userJuridiqueRepository;
    }

    /**
     * @Route("/_partials_process_stats/{obj}/{perms}", name="_partials_process_stats")
     */
    public function _process_stats($obj, $perms, ManagerRegistry $doctrine){
        //Récupération de la classe concernée
        $classe = self::REPOS[$obj];

        $counts = [];
        $repo = $doctrine->getRepository($classe);

        /**
         * @var User $user
         */
        $user = $this->getUser();

        if($perms === 'all'){
            $counts['nbrDemandes'] = count($repo->findAll());
            $counts['encours'] = count($repo->findBy([
                'currentState' => ['en_attente_manager', 'demande_non_attribuee', 'demande_attribuee']
            ]));
            $counts['validees'] = count($repo->findBy([
                'currentState' => 'demande_validee'
            ]));
            $counts['rejetees'] = count($repo->findBy([
                'currentState' => 'demande_rejetee'
            ]));
        }else if($perms === 'all_user_juridique'){
            $counts['en_attente'] = count($repo->findBy([
                'userAgentJuridique' => $this->userJuridiqueRepository->findOneBy([
                    'user' => $user
                ]),
                'currentState' => 'demande_attribuee'
            ]));
            $counts['validees'] = count($repo->findBy([
                'userAgentJuridique' => $this->userJuridiqueRepository->findOneBy([
                    'user' => $user
                ]),
                'currentState' => 'demande_validee'
            ]));
            $counts['rejetees'] = count($repo->findBy([
                'userAgentJuridique' => $this->userJuridiqueRepository->findOneBy([
                    'user' => $user
                ]),
                'currentState' => 'demande_rejetee'
            ]));
            $counts['total'] = $counts['validees'] + $counts['rejetees'];
        }else if($perms === 'all_user'){
            $counts['all'] = count($repo->findBy([
                'createdBy' => $user
            ]));
            $counts['attente_manager'] = count($repo->findBy([
                'createdBy' => $user,
                'currentState'=> 'en_attente_manager'
            ]));
            $counts['rejetees'] = count($repo->findBy([
                'createdBy' => $user,
                'currentState'=> 'demande_rejetee'
            ]));
            $counts['traitees'] = count($repo->findBy([
                'createdBy' => $user,
                'currentState'=> 'demande_validee'
            ]));
        }else if($perms === 'all_manager'){
            $counts['all'] = count($repo->findBy([
                'departementInitiateur' => $user->getDepartement()
            ]));
            $counts['en_attente'] = count($repo->findBy([
                'departementInitiateur' => $user->getDepartement(),
                'currentState' => 'en_attente_manager'
            ]));
            $counts['validees'] = count($repo->findBy([
                'departementInitiateur' => $user->getDepartement(),
                'currentState' => 'demande_validee'
            ]));
            $counts['rejetees'] = count($repo->findBy([
                'departementInitiateur' => $user->getDepartement(),
                'currentState' => 'demande_rejetee'
            ]));
        }

        return $this->render('apps/gestion_process/_partials/stats/'.$obj.'/'.$perms.'.html.twig', [
            'counts' => $counts
        ]);
    }

    /**
     * @Route("/_partials_process_table/{obj}/{perms}/{status}", name="_partials_process_table")
     */
    public function _partials_process_table($obj, $perms, $status = 'all', ManagerRegistry $doctrine){
        /**
         * @var User $user
         */
        $user = $this->getUser();

        //Récupération de la classe concernée
        $classe = self::REPOS[$obj];

        $repo = $doctrine->getRepository($classe);
        switch($status){
            case 'all_user':
                $avis = $repo->findBy([
                    'createdBy' => $user
                ]);
                break;
            case 'en_attente_user_manager':
                $avis = $repo->findBy([
                    'createdBy' => $user,
                    'currentState' => 'en_attente_manager'
                ]);
                break;
            case 'en_attente_user_service_juridique':
                $avis = $repo->findBy([
                    'currentState' => ['demande_non_attribuee', 'demande_attribuee'],
                    'createdBy' => $user,
                ]);
                break;
            case 'rejetees_user':
                $avis = $repo->findBy([
                    'createdBy' => $user,
                    'currentState' => ['demande_rejetee_manager', 'demande_rejetee'],
                ]);
                break;
            case 'finalises_user':
                $avis = $repo->findBy([
                    'createdBy' => $user,
                    'currentState' => 'demande_validee'
                ]);
                break;

            case 'all':
                $avis = $repo->findAll();
                break;
            case 'boss_juridique_attente_manager':
                $avis = $repo->findBy([
                    'currentState' => 'en_attente_manager'
                ]);
                break;
            case 'boss_juridique_attente_attribution':
                $avis = $repo->findBy([
                    'currentState' => 'demande_non_attribuee'
                ]);
                break;
            case 'boss_juridique_attente_traitement':
                $avis = $repo->findBy([
                    'currentState' => 'demande_attribuee'
                ]);
                break;
            case 'boss_juridique_attente_validee':
                $avis = $repo->findBy([
                    'currentState' => 'demande_validee'
                ]);
                break;
            case 'boss_juridique_attente_rejetee':
                $avis = $repo->findBy([
                    'currentState' => 'demande_rejetee'
                ]);
                break;

            case 'all_user_juridique':
                $avis = $repo->findBy([
                    'userAgentJuridique' => $this->userJuridiqueRepository->findOneBy([
                        'user' => $user
                    ])
                ]);
                break;
            case 'new_user_juridique':
                $avis = $repo->findBy([
                    'userAgentJuridique' => $this->userJuridiqueRepository->findOneBy([
                        'user' => $user
                    ]),
                    'currentState' => 'demande_attribuee'
                ]);
                break;
            case 'rejetees_user_juridique':
                $avis = $repo->findBy([
                    'userAgentJuridique' => $this->userJuridiqueRepository->findOneBy([
                        'user' => $user
                    ]),
                    'currentState' => 'demande_rejetee'
                ]);
                break;
            case 'validees_user_juridique':
                $avis = $repo->findBy([
                    'userAgentJuridique' => $this->userJuridiqueRepository->findOneBy([
                        'user' => $user
                    ]),
                    'currentState' => 'demande_validee'
                ]);
                break;


            case 'all_manager':
                $avis = $repo->findBy([
                    'departementInitiateur' => $user->getDepartement()
                ]);
                break;
            case 'all_manager_rejetees_self':
                $avis = $repo->findBy([
                    'departementInitiateur' => $user->getDepartement(),
                    'currentState' => 'demande_rejetee_manager'
                ]);
                break;
            case 'all_manager_attente_self':
                $avis = $repo->findBy([
                    'departementInitiateur' => $user->getDepartement(),
                    'currentState' => 'en_attente_manager'
                ]);
                break;
            case 'all_manager_attente_juridique':
                $avis = $repo->findBy([
                    'departementInitiateur' => $user->getDepartement(),
                    'currentState' => ['demande_non_attribuee', 'demande_attribuee']
                ]);
                break;
            case 'all_manager_rejet_juridique':
                $avis = $repo->findBy([
                    'departementInitiateur' => $user->getDepartement(),
                    'currentState' => 'demande_rejetee'
                ]);
            case 'all_manager_ok_juridique':
                $avis = $repo->findBy([
                    'departementInitiateur' => $user->getDepartement(),
                    'currentState' => 'demande_validee'
                ]);
                break;
        }

        return $this->render('apps/gestion_process/_partials/tables/'.$obj.'.html.twig', [
            'obj' => $obj,
            'status' => $status,
            'perms' => $perms,
            'avis' => $avis
        ]);
    }

    /**
     * @Route("_partials_process_filters/{obj}/{perms}/{status}", name="_partials_filters_process")
     */
    public function _partials_filters_process($obj, $perms, $status){
        return $this->render('apps/gestion_process/_partials/tables/filters/'.$obj.'/'.$perms.'.html.twig', [
            'status' => $status,
            'processObj' => $obj
        ]);
    }
}