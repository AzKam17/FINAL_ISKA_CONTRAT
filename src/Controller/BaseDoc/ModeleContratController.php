<?php

namespace App\Controller\BaseDoc;

use App\Entity\BaseDoc\ModeleContrat;
use App\Repository\BaseDoc\ModeleContratRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @IsGranted("ROLE_JURIDIQUE")
 * @Route("/apps/base-documentaire/modele-contrat")
 */
class ModeleContratController extends AbstractController
{
    /**
     * @Route("/", name="apps_modele_contrat")
     */
    public function index(
        Request $request,
        ModeleContratRepository $repository,
        SluggerInterface $slugger,
        EntityManagerInterface $manager
    ): Response
    {
        $modContrat = new ModeleContrat();

        $form = $this->createFormBuilder($modContrat)
            ->add('label', TextType::class, [
                'empty_data' => '',
                'required' => false,
                'mapped' => false,
                'label' => 'Nom du fichier',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('fichier', FileType::class, [
                'label' => 'Joindre le fichier',
                'mapped' => false,
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
            ])
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //Fichier dans le formulaire
            /** @var UploadedFile $modeleContratFile */
            $fichier = $form->get('fichier')->getData();

            $label = $form->get('label')->getData();
            if($fichier){
                $orginalFileName = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($orginalFileName);
                $newFilename = md5($label).'-'.uniqid().'.'.$fichier->guessExtension();
                try{
                    $fichier->move(
                        $this->getParameter('mod_contrat_folder'),
                        $newFilename
                    );
                }catch(FileException $e){
                    echo $e->getMessage();
                }
                $modContrat
                    ->setOriginalName($label)
                    ->setPath(
                        '/uploads/mod_contrat/'.$newFilename
                    );
                $this->addFlash(
                    'success',
                    'Le type de contrat a bien enregistré.'
                );
                $manager->persist($modContrat);
                $manager->flush();
            }
        }

        return $this->render('apps/base_documentaire/index.html.twig', [
            'form' => $form->createView(),
            'modeles' => $repository->findAll()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="apps_modele_contrat_delete", methods={"GET"})
     */
    public function delete(Request $request, ModeleContrat $modeleContrat,ModeleContratRepository $modeleContratRepository){
        $this->addFlash(
            'success',
            'Le modèle de contrat <b>' . $modeleContrat->getId() . '</b> a bien été supprimé.'
        );
        $modeleContratRepository->remove($modeleContrat);
        return $this->redirectToRoute('apps_modele_contrat', [], Response::HTTP_SEE_OTHER);
    }
}
