@component('mail::message')
# Set Up Your Account Password

You have been registered in our system. Please click the button below to set up your password.

@component('mail::button', ['url' => $setupUrl])
Set Up Password
@endcomponent

This link will expire in 24 hours.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
