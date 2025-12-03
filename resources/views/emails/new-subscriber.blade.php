<x-mail::message>


thank you for subscribing to our newsletter.

<x-mail::button :url="route('frontend.home')">
Visit our News Site
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
