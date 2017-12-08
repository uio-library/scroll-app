<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminNoticeNewUser extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $adminEmail;

    /**
     * Create a new message instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->adminEmail = config('auth.activation_admin_email');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->to($this->adminEmail);
        $this->subject('New Scroll account pending activation');

        $userLink = route('users.show', $this->user);

        return $this->markdown('emails.admin_notice_new_user')
            ->with([
                'name' => $this->user->name,
                'email' => $this->user->email,
                'userLink' => $userLink,
            ]);
    }
}
