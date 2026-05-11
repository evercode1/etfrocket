<x-mail::message>
# Account Verification

Hello {{ $name }},

Please verify your email address by clicking the button below:

<x-mail::button :url="$url">
Verify Email Address
</x-mail::button>

If the button above doesn't work, you can also use this link:  
[{{ $url }}]({{ $url }})

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>