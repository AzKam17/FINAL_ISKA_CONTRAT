<?php

namespace App\Service;

use App\Entity\Abstracts\ProcessObj;
use App\Entity\AvisConseils\Avis;
use App\Entity\User;
use Carbon\CarbonImmutable;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Workflow\WorkflowInterface;

class ValidationManagerProcess
{
    /**
     * @var MailerInterface
     */
    private $mailer;
    /**
     * @var WorkflowInterface
     */
    private $workflow;

    public function __construct(MailerInterface $mailer, WorkflowInterface $gestionProcessStateMachine)
    {
        $this->mailer = $mailer;
        $this->workflow = $gestionProcessStateMachine;
    }

    public function run(User $user, $processObj, $decision) : ProcessObj
    {
        if(!($processObj instanceof ProcessObj)){
            throw new \Exception("La classe transmise n'est pas un objet ProcessObj !");
        }

        $processObj
            ->setActionManagerAt(
                CarbonImmutable::now()
            );

        //Etape Current State
        if($decision){
            if($this->workflow->can($processObj, 'valider_manager')){
                $this->workflow->apply($processObj, 'valider_manager');
            }
        }else{
            if($this->workflow->can($processObj, 'refuser_manager')){
                $this->workflow->apply($processObj, 'refuser_manager');
            }
        }

        if($processObj instanceof Avis){
            $email = (new Email())
                ->from('tests@example.com')
                ->to($user->getEmail())
                ->subject("Votre demande d'avis a été transmise.")
                ->html(
                    '<p>Votre demande d\'avis a été validée par '. $user->displayName() .'. Elle se trouve désormais au niveau du service juridique pour traitement.</p>'
                );

        }

        $this->mailer->send($email);
        return $processObj;
    }
}