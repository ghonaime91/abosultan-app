@component('mail::message')

# {{ __("notifications.email_greeting") }}, {{ $notifiable->first_name }}

{{ $message }}

<h2 style="text-align: center; font-size: 24px; color: red; font-weight: bold;">{{ $otp }}</h2>

{{ __("notifications.thanks") }},  
{{ config('app.name') }}

@endcomponent
