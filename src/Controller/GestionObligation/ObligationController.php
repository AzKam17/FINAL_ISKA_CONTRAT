<?php

namespace App\Controller\GestionObligation;

use App\Entity\Obligation\Document;
use App\Entity\Obligation\Obligation;
use App\Entity\Obligation\PlanAction;
use App\Entity\Obligation\Preuve;
use App\Entity\User;
use App\Form\GestionObligation\ObligationType;
use App\Repository\Obligation\ObligationRepository;
use App\Repository\UserJuridiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @IsGranted("ROLE_JURIDIQUE")
 * @Route("/apps/obligation")
 */
class ObligationController extends AbstractController
{
    /**
     * @Route("/", name="apps_obligation_home", methods={"GET"})
     */
    public function index(ObligationRepository $obligationRepository): Response
    {
        $allObligations = $obligationRepository->findBy(
            array(), array('id' => 'DESC')
        );
        return $this->render('apps/gestion_obligation/index.html.twig', [
            'obligations' => $allObligations,
            'nombreTotal' => count($allObligations),
        ]);
    }

    /**
     * @Route("/new", name="apps_obligation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ObligationRepository $obligationRepository, SluggerInterface $slugger,  EntityManagerInterface $manager, UserJuridiqueRepository $userJuridiqueRepository): Response
    {
        $obligation = (new Obligation())->addPreuve((new Preuve())->addAction(new PlanAction()));

        $form = $this->createForm(ObligationType::class, $obligation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Fichier dans le formulaire
            /** @var UploadedFile $modeleContratFile */
            $fichier = $form->get('fichier')->getData();

            /** @var User $user */
            $user = $this->getUser();
            $obligation->setResponsable(
                $userJuridiqueRepository->findOneBy(['user' => $user])
            )->setPrevues("");
            $manager->persist($obligation);

            if ($fichier) {
                $orginalFileName = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($orginalFileName);
                $newFilename = uniqid() . '.' . $fichier->guessExtension();

                try {
                    $fichier->move(
                        $this->getParameter('obligation_folder'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    echo $e->getMessage();
                }

                $doc = (new Document())
                    ->setOriginalName($newFilename)
                    ->setPath(
                        '/uploads/obligation/' . $newFilename
                    );

                $this->addFlash(
                    'success',
                    'L\'obligation a bien enregistrée.'
                );

                $obligation->setDocument(
                    $doc
                );
                $manager->persist($doc);
            }

            foreach ($form->get('preuves') as $p){
                $fichier = $p->get('fichiers')->getData();
                /** @var Preuve $preuve */
                $preuve = $p->getData();
                dump($preuve);
                foreach($preuve->getActions() as $a){
                    $a->setPreuve($preuve);
                    //$manager->persist($a);
                }
                if ($fichier) {
                    $orginalFileName = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($orginalFileName);
                    $newFilename = uniqid() . '.' . $fichier->guessExtension();
                    try {
                        $fichier->move(
                            $this->getParameter('preuves_folder'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        echo $e->getMessage();
                    }
                    $doc = (new Document())
                        ->setOriginalName($newFilename)
                        ->setPath(
                            '/uploads/obligation_preuves/' . $newFilename
                        );
                    $manager->persist($doc);
                    $preuve->setPieceJustificatif($doc);
                }
                $preuve->setObligation($obligation);
                $manager->persist($preuve);
            }
            $manager->flush();
            return $this->redirectToRoute('apps_obligation_home', [], Response::HTTP_SEE_OTHER);

        }

        return $this->renderForm('apps/gestion_obligation/new.html.twig', [
            'obligation' => $obligation,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="apps_obligation_show", methods={"GET"})
     */
    public function show(Obligation $obligation): Response
    {
        $form = $this->createForm(ObligationType::class, $obligation, [
            'show' => true
        ]);
        return $this->renderForm('apps/gestion_obligation/show.html.twig', [
            'obligation' => $obligation,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="apps_obligation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Obligation $obligation, ObligationRepository $obligationRepository): Response
    {
        $form = $this->createForm(ObligationType::class, $obligation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $obligationRepository->add($obligation);
            return $this->redirectToRoute('app_gestion_obligation_obligation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('apps/gestion_obligation/edit.html.twig', [
            'obligation' => $obligation,
            'form' => $form,
        ]);
    }

}
