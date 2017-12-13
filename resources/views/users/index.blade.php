@extends('layout')

@section('content')
    <h2>Brukere</h2>

    <ul>
        @foreach($users as $user)
            <li>
                <a href="{{ action('UsersController@show', $user->id) }}">{{ $user->name }}</a>
                @if ($user->hasIntegration('github'))
                    <span class="fa fa-github"></span>
                @endif
                @if ($user->hasIntegration('webid'))
                    <span class="fa fa-university"></span>
                @endif
            </li>
        @endforeach
    </ul>
@endsection