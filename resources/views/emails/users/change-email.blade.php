@component('mail::message')
# Email Change Validation

Hello {{$user->firstName}} {{$user->lastName}},

A change of email address has been initiated.

Use the code below to complete the email validation.
Once your new email address has been validated you will be able to login with that new email.

**CODE:** {{$code}}

The button below will take you to the validation form.

@component('mail::button', ['url' => config('app.web') . '/auth/verify-email/' . $code, 'color' => 'primary'])
Validate Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
