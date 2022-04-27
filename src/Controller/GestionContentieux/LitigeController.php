<?php

namespace App\Controller\GestionContentieux;

use App\Entity\GestionContentieux\Litige;
use App\Entity\GestionContentieux\Procedure;
use App\Entity\User;
use App\Entity\UserJuridique;
use App\Form\GestionContentieux\LitigeType;
use App\Form\GestionLitiges\NewLitige;
use App\Repository\GestionContentieux\LitigeRepository;
use App\Repository\GestionContentieux\ProcedureRepository;
use App\Repository\UserJuridiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_JURIDIQUE")
 * @Route("/apps/litiges")
 */
class LitigeController extends AbstractController
{
    /**
     * @Route("/", name="apps_home_litige", methods={"GET"})
     */
    public function index(LitigeRepository $litigeRepository): Response
    {
        $allLitiges = $litigeRepository->findBy(
            array(),
            array('id' => 'DESC')
        );
        return $this->render('apps/gestion_litiges/index.html.twig', [
            'litiges' => $allLitiges,
            'nbrDemandeEnCoursDeValidation' => count($allLitiges)
        ]);
    }

    /**
     * @Route("/new", name="apps_litige_new", methods={"GET", "POST"})
     */
    public function new(Request $request, LitigeRepository $litigeRepository, ProcedureRepository $procedureRepository, UserJuridiqueRepository $userJuridiqueRepository): Response
    {
        $litige = new Litige();
        $form = $this->createForm(NewLitige::class, $litige);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /* @var User $user */
            $user = $this->getUser();
            $userJ = $userJuridiqueRepository->findOneBy([
                'user' => $user
            ]);

            $litige
                ->addProcedure(
                    (new Procedure())
                        ->setCreatedBy($userJ)
                        ->setValue(
                            $form
                                ->get('procedure')
                                ->getData() ?? "Début du litige"
                        )
                )
                ->setCreatedBy($userJ)
            ;

            $litigeRepository->add($litige);
            $this->addFlash(
                "success",
                "Litige enregistré avec succès. Son identifiant est {$litige->getId()}."
            );
            return $this->redirectToRoute('apps_home_litige', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('apps/gestion_litiges/new.html.twig', [
            'litige' => $litige,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="apps_litige_show", methods={"GET"})
     */
    public function show(Litige $litige): Response
    {
        $form = $this->createForm(NewLitige::class, $litige);

        return $this->render('apps/gestion_litiges/show.html.twig', [
            'litige' => $litige,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="apps_litige_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Litige $litige, LitigeRepository $litigeRepository, ProcedureRepository $procedureRepository, UserJuridiqueRepository $userJuridiqueRepository): Response
    {
        $form = $this->createForm(NewLitige::class, $litige);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /* @var User $user */
            $user = $this->getUser();

            $procedure = (new Procedure())
                ->setCreatedBy(
                    $userJuridiqueRepository->findOneBy([
                        'user' => $user
                    ])
                )
                ->setValue(
                    $form->get('procedure')->getData()
                );

            $procedureRepository->add($procedure);

            $litige->addProcedure($procedure);

            $litigeRepository->add($litige);
            $this->addFlash(
                "success",
                "Litige {$litige->getId()} modifié avec succès."
            );
            return $this->redirectToRoute('apps_home_litige', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('apps/gestion_litiges/edit.html.twig', [
            'litige' => $litige,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/close", name="apps_litige_close")
     */
    public function close(Litige $litige, LitigeRepository $litigeRepository,UserJuridiqueRepository $userJuridiqueRepository,EntityManagerInterface $manager)
    {
        /* @var User $user */
        $user = $this->getUser();
        $userJ = $userJuridiqueRepository->findOneBy([
            'user' => $user
        ]);
        $litige
            ->setIsClosed(true)
            ->addProcedure(
            (new Procedure())
            ->setValue('Procédure close')
            ->setCreatedBy($userJ)
        );
        $manager->persist($litige);
        $manager->flush();
        return $this->redirectToRoute('apps_litige_show', [
            'id' => $litige->getId()
        ]);
    }
}
