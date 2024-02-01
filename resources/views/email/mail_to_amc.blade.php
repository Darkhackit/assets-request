<x-mail::message>
    Dear AMC
    You have received a new request from {{$invoice?->user->name}}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
