<x-mail::message>
Click the button below to log in to your account. This link will expire in 15 minutes.

<x-mail::button :url="$url">
Log In via Link
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>