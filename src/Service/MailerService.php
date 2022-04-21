<?php

namespace App\Service;
use http\Encoding\Stream\Inflate;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\Reservation;
use App\Entity\Event;

class MailerService
{
    private $mailer;


    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    public function sendEmail(string $subject,string $from,string $to):void

    {         $r = new Reservation(); //objet create instance

        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject($subject)
        ->embedFromPath('Front-office/images/logo.png',  'logo shared')
        ->html(' <p>Bonjour cher(e) Mr/Mme </p><br> <p>Votre réservation a été passée avec succés </p>');


        $this->mailer->send($email);
    }


    public function sendEmailevent(string $subject,string $from,string $to):void

    {         $r = new Event(); //objet create instance

        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->embedFromPath('Front-office/images/logo.png',  'logo shared')
            ->html(' <p>Bonjour cher(e) Mr/Mme </p><br> <p>Vous avez ajouté un évènement avec succés</p> ');


        $this->mailer->send($email);
    }

}