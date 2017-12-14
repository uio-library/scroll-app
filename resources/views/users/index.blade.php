@extends('layout')

@section('content')
    <h2>Brukere</h2>

    <ul>
        @foreach($users as $user)
            <li>
                <a href="{{ action('UsersController@show', $user->id) }}">{{ $user->name }}</a>
                @if ($user->hasIntegration('github'))
                    <span class="fa fa-github" title="GitHub"></span>
                @endif
                @if ($user->hasIntegration('webid'))
                    <span class="fa fa-university" title="UiO WebID"></span>
                @endif
            </li>
        @endforeach
    </ul>
@endsection
