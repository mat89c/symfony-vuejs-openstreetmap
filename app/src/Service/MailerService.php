<?php

namespace App\Service;
use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

class MailerService
{
    private $mailer;

    private $translator;

    public function __construct(MailerInterface $mailer, TranslatorInterface $translator)
    {
        $this->mailer = $mailer;
        $this->translator = $translator;
    }

    public function userRegistered(User $user): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address($_ENV['SENDER_EMAIL'], 'Demp App'))
            ->to(new Address($user->getEmail(), $user->getName()))
            ->subject($this->translator->trans('registration_email.subject'))
            ->htmlTemplate('email/registration.html.twig')
            ->context([
                'token' => $user->getToken(),
                'appUrl' => $_ENV['FRONTEND_APP_URL'],
            ]);

        $this->mailer->send($email);
    }
}