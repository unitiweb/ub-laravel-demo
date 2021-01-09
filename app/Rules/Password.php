<?php

namespace App\Rules;

use App\Facades\Services\AuthService;
use Illuminate\Contracts\Validation\Rule;

/**
 * Validate the password by checking various character types such as upper, lower, numbers, etc...
 *
 * @package App\Rules
 */
class Password implements Rule
{
    /**
     * The error message text
     *
     * @var string
     */
    protected $messageText;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $value = AuthService::decodePassword($value);
        $result = AuthService::checkPasswordCharacters($value);

        if ($result->count() >= 1) {
            $this->messageText = 'The :attribute failed: ' . $result->implode(', ');
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->messageText;
    }
}
