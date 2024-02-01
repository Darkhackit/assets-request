<x-mail::message>
    Dear #{{$invoice?->user?->name}}

    your request has been declined. Contact AMC for further information

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
