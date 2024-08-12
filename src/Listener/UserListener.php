<?php

namespace App\Listener;

use App\Entity\User;
use App\Service\Mailer\MailerService;

class UserListener
{
    public function __construct(private MailerService $mailer)
    {
    }

    public function postPersistEvent(User $user): void
    {
        $this->mailer->sendUserCreatedEmail($user);
    }
}
