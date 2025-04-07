<x-mail::message>
# {{ $subject }}

{{ $message }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
