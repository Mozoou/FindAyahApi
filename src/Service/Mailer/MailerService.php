<?php

namespace App\Service\Mailer;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailerService
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function sendUserCreatedEmail(User $user): void
    {
        $emailMessage = (new TemplatedEmail())
            ->from(new Address('findayah@app.com', 'FindAyah App'))
            ->to($user->getEmail())
            ->subject('Welcome on FindAyah')
            ->htmlTemplate('registration/welcome_email.html.twig');

        $this->mailer->send($emailMessage);
    }
}