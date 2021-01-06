@component('mail::message')
# Thank you for Registering

Hello {{$user->firstName}} {{$user->lastName}},

Your account registration for **{{config('app.name')}}** was successful and is ready to use.

You may now use the button below to login.

@component('mail::button', ['url' => config('app.url') . '/auth/login', 'color' => 'primary'])
Login to {{config('app.name')}}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
