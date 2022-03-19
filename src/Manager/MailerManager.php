<?php

namespace App\Manager;

use App\Entity\Donation;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class MailerManager
{
    /** @var MailerInterface */
    protected $mailer;
    /** @var UrlGeneratorInterface */
    protected $router;
    /** @var Environment */
    protected $twig;
    /** @var string */
    protected $defaultName;
    /** @var string */
    protected $defaultEmail;

    public function __construct(
        MailerInterface $mailer,
        Environment $twig,
        string $defaultName,
        string $defaultEmail
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->defaultName = $defaultName;
        $this->defaultEmail = $defaultEmail;
    }

    public function sendContactMessage($data)
    {
        $this->sendMessage(
            'Nouvelle demande de contact sur le site',
            'mail/contact.html.twig',
            $data
        );
    }

    private function sendMessage(string $subject, string $templateName, array $data = [], $to = null)
    {
        $from = new Address($this->defaultEmail, $this->defaultName);
        $replyTo = new Address($this->defaultEmail, $this->defaultName);

        if (null === $to) {
            $to = new Address($this->defaultEmail, $this->defaultName);
        }

        $message = (new Email())
            ->from($from)
            ->replyTo($replyTo)
            ->to($to)
            ->subject($subject)
            ->html($this->twig->render($templateName, $data))
        ;

        $this->mailer->send($message);
    }
}
