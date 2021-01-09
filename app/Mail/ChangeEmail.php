<?php

namespace App\Mail;

use App\Facades\Services\TokenService;
use App\Models\Token;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Send an email when an email change has been initiated
 *
 * @package App\Mail
 */
class ChangeEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The user the verify email will be sent to
     *
     * @var User
     */
    protected $user;

    /**
     * Create a new message instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return self
     */
    public function build(): self
    {
        // Generate and return the token with the validation code
        $token = TokenService::createCode($this->user, Token::EMAIL_VERIFY);

        // Save the new email to the data field of the token
        $token->save();

        // Send the email
        return $this->from(config('app.mail.from'))
            ->markdown('emails.users.change-email', [
                'user' => $this->user,
                'code' => $token->token,
            ]);
    }
}
