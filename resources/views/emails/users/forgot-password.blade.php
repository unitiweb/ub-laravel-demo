@component('mail::message')
# Reset Password

Hello {{$user->firstName}} {{$user->lastName}},

A password reset was initiated. If this was you then you can use the code and button below
to reset your password.

**CODE:** {{$code}}

The button below will take you to the reset password form.

@component('mail::button', ['url' => config('app.url') . '/auth/forgot-password-validate', 'color' => 'primary'])
    Reset Password
@endcomponent

Thanks,

{{ config('app.name') }}
@endcomponent
