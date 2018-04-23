<?php

namespace App\Observers;

use App\Mail\AdminNoticeNewUser;
use App\Mail\Welcome;
use App\User;
use Illuminate\Mail\Mailer;

class UserObserver
{
    protected $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function created(User $user)
    {
        \Session::flash('status', 'En konto har blitt opprettet, men du må få tilgang til å redigere kurs før du kan gjøre noe smart.');

        $this->mailer->send(new AdminNoticeNewUser($user));
        $this->mailer->send(new Welcome($user));
    }
}
