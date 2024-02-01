<x-mail::message>
    Hi {{$user->name}}

    Your email is {{$user->email}}
    Your password is {{$user->save_password}}

   Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
