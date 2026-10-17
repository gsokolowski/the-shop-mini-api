<x-mail::message>
# Thanks for your order, {{ $order->user->name }}!

You bought **{{ $order->product->name }}** for **{{ $order->price }}**.

<x-mail::button :url="$appUrl">
Visit the shop
</x-mail::button>

Thanks,<br>
{{ $appName }}
</x-mail::message>