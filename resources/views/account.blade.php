@extends('layout')

@section('content')

    <div class="card">
        <div class="card-header">Account</div>


        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex">
                <span class="col-3">Name:</span>
                <span class="col-8">{{ $user->name }}</span>
            </li>
            <li class="list-group-item d-flex">
                <span class="col-3">E-mail:</span>
                <span class="col-8">{{ $user->email }}</span>
            </li>
            <li class="list-group-item d-flex">
                <span class="col-3">Registered:</span>
                <span class="col-8">{{ $user->created_at->formatLocalized('%d %B %Y') }}</span>
            </li>
            <li class="list-group-item d-flex">
                <span class="col-3">Rights:</span>
                <span class="col-8">
                    @foreach ($user->rights as $right)
                        <span class="badge badge-pill badge-warning">{{ $right->name }}</span>
                    @endforeach
                </span>
            </li>
        </ul>

        <div class="card-header">Integrations</div>

        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex align-items-center">
                <span class="col-3">WebID:</span>
                <span class="col-8">
                    @if($webid)
                        <i class="text-success fa fa-check"></i>
                        {{ $webid->account_id }}
                    @else
                        <a href="{{ route('saml_login') }}" class="btn btn-success btn-sm">
                            <i class="fa fa-link"></i>
                            Connect</a>
                    @endif
                </span>
            </li>
            <li class="list-group-item d-flex align-items-center">
                <span class="col-3">GitHub:</span>
                <span class="col">
                    @if($github)
                        <i class="text-success fa fa-check"></i>
                        <a href="https://github.com/{{ $github->account_id }}">{{ $github->account_id }}</a>
                    @else
                        <a href="{{ route('github.connect') }}" class="btn btn-success btn-sm">
                            <i class="fa fa-link"></i>
                            Connect</a>
                    @endif
                </span>
                <span class="col-auto">
                    @if($github)
                        <a href="{{ route('github.disconnect') }}" class="btn btn-outline-danger btn-sm">
                            <i class="fa fa-unlink"></i>
                            Remove</a>
                    @endif
                </span>
            </li>
        </ul>

    </div>

@endsection