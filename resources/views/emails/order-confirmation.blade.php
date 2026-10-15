<x-mail::message>
# Thanks for your order, {{ $order->user->name }}!

You bought **{{ $order->product->name }}** for **{{ $order->price }}**.

<x-mail::button :url="config('app.url')">
Visit the shop
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>