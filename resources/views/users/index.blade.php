@extends('layout')

@section('content')
    <h2>Brukere</h2>

    <ul>
        @foreach($users as $user)
            <li>
                <a href="{{ action('UsersController@show', $user->id) }}">{{ $user->name }}</a>
                ({{ $user->activate ? 'aktivert' : 'ikke aktivert enda' }})
            </li>
        @endforeach
    </ul>
@endsection