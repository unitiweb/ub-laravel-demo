<?php

namespace App\Models;

/**
 * Class Token
 *
 * @package App\Models
 *
 * @property string token
 * @property string data
 */
class Token extends BaseModel
{
    const TYPE_ACCESS = 'access';
    const TYPE_REFRESH = 'refresh';
    const EMAIL_VERIFY = 'email-verify';
    const PASSWORD_RESET = 'password-reset';
    const PASSWORD_RESET_VALIDATE = 'password-reset-validate';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'userUid',
        'tokenType',
        'token',
        'expiresAt',
    ];
}
