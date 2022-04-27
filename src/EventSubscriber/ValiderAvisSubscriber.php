<?php

namespace App\EventSubscriber;

use App\Entity\Abstracts\ProcessObj;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Workflow\Event\Event;

class ValiderAvisSubscriber implements EventSubscriberInterface
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function onWorkflowGestionProcessTransition(Event $event)
    {
        /* @var ProcessObj $obj */
        $obj = $event->getSubject();

        $this->mailer->send(
            (new Email())
                ->from('tests@example.com')
                ->to($obj->getCreatedBy()->getEmail())
                ->subject("Nouvelle demande d'avis")
                ->html('Votre demande d\'avis N°' . $obj->getId() .' a bien été transmise au service juridique.')
        );
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.gestion_process.transition.valider_avis' => 'onWorkflowGestionProcessTransition',
        ];
    }
}
