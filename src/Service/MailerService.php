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
    protected $mailer;
    protected $router;
    protected $twig;


    public function __construct(\Swift_Mailer $mailer, RouterInterface $router, \Twig\Environment $twig, LoggerInterface $logger, $noreply)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = $twig;

    }
    protected function sendMessage($templateName, $context, $fromEmail, $toEmail): bool
    {
        $context = $this->twig->mergeGlobals($context);
        $template = $this->twig->load($templateName);
        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);

        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail);

        if (!empty($htmlBody)) {
            $message->setBody($htmlBody, 'text/html')->addPart($textBody, 'text/plain');
        } else {
            $message->setBody($textBody);
        }
        $result = $this->mailer->send($message);

        $log_context = ['to' => $toEmail, 'message' => $textBody, 'template' => $templateName];
        if ($result) {
            $this->logger->info('SMTP email sent', $log_context);
        } else {
            $this->logger->error('SMTP email error', $log_context);
        }

        return $result;
    }

   /* public function sendEmailevent(string $subject,string $from,string $to):void

    {         $r = new Event(); //objet create instance

        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->embedFromPath('Front-office/images/logo.png',  'logo shared')
            ->html(' <p>Bonjour cher(e) Mr/Mme </p><br> <p>Vous avez ajouté un évènement avec succés</p> ');


        $this->mailer->send($email);
    }*/

}