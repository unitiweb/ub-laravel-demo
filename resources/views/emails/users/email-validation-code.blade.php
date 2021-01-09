@component('mail::message')
# Email Validation

Hello {{$user->firstName}} {{$user->lastName}},

Your email address needs to be validated. Use the code below or the button to complete the validation.

**CODE:** {{$code}}

The button below will take you to the validation form.

@component('mail::button', ['url' => config('app.web') . '/auth/verify-email/' . $code, 'color' => 'primary'])
Validate Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
