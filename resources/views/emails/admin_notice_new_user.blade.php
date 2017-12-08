@component('mail::message')

A new user just logged in:

@component('mail::table')
| Name          | Email         |
| ------------- |:-------------:|
| {{ $name }}   | {{ $email }}  |
@endcomponent

@component('mail::button', ['url' => $userLink, 'color' => 'green' ])
View user
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
