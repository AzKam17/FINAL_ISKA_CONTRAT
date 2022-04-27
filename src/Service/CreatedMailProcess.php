<?php

namespace App\Service;

use App\Entity\Abstracts\ProcessObj;
use App\Entity\AvisConseils\Avis;
use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class CreatedMailProcess
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function run(User $user, $processObj){
        $email = (new Email())
            ->from('tests@example.com')
            ->to($user->getEmail())
            ->subject('TESTS')
                ->html('TESTS');

        if($processObj instanceof Avis){
            $email = (new Email())
                ->from('tests@example.com')
                ->to($user->getEmail())
                ->subject('Création demande d\'avis')
                ->html('<p>Votre demande d\'avis a bien été créee.</p>');

        }

        $this->mailer->send($email);
    }
}