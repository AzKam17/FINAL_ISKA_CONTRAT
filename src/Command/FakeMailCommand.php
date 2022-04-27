<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FakeMailCommand extends Command
{
    protected static $defaultName = 'tests:mail';
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(string $name = null, MailerInterface $mailer)
    {
        parent::__construct($name);
        $this->mailer = $mailer;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        for ($i = 0; $i <300; $i++){
            $email = (new Email())
                ->from('hello'.$i.'@example.com')
                ->to('you'.$i.'@example.com')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Time '.$i.' for Symfony Mailer!')
                ->text('Sending '.$i.' emails is fun again!')
                ->html('<p>See Twig integration for better HTML integration!</p>');

            $this->mailer->send($email);
        }
        return Command::SUCCESS;
        // return Command::FAILURE;
        // return Command::INVALID
    }
}