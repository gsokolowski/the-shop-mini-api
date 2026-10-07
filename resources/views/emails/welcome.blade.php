<x-mail::message>
# Welcome, {{ $user->name }}!

Thanks for registering at The Shop.

<x-mail::button :url="config('app.url')">
Visit the shop
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>