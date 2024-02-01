<x-mail::message>
    Dear {{$invoice->user?->name}}


    Your request has been sent to AMC <br/>
    And its currently been worked on

    Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
