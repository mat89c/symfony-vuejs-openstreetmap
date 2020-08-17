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
        $this->sender = $_ENV['SENDER_EMAIL'];
        $this->appName = $_ENV['APP_NAME'];
        $this->appUrl = $_ENV['FRONTEND_APP_URL'];
    }

    public function userRegistered(User $user): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->sender, $this->appName))
            ->to(new Address($user->getEmail(), $user->getName()))
            ->subject($this->translator->trans('email.registration.subject', ['%appName%' => $this->appName]))
            ->htmlTemplate('email/registration.html.twig')
            ->context([
                'token' => $user->getToken(),
                'appUrl' => $this->appUrl,
                'appName' => $this->appName,
            ]);

        $this->mailer->send($email);
    }

    public function resetPassword(User $user): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->sender, $this->appName))
            ->to(new Address($user->getEmail(), $user->getName()))
            ->subject($this->translator->trans('email.reset_password.subject', ['%appName%' => $this->appName]))
            ->htmlTemplate('email/reset_password.html.twig')
            ->context([
                'token' => $user->getToken(),
                'appUrl' => $this->appUrl,
                'appName' => $this->appName,
            ]);

        $this->mailer->send($email);
    }
}