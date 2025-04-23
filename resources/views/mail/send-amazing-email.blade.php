<x-mail::message>
# {{ $subject }}

From, {{ $email }}

{{ $message }}

{{ config('app.name') }}
</x-mail::message>
