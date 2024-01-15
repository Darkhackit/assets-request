<x-mail::message>
    Hi #{{$invoice?->user?->name}}

    {{$invoice->comments}}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
