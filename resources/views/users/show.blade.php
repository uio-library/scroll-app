@extends('layout')

@section('content')
    <div class="row justify-content-md-center">
        <div class="col-md-8 col-md-offset-2">
            <div class="card">
                <div class="card-header">Account</div>


                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex">
                        <span class="col-4">Name:</span>
                        <span class="col-8">{{ $user->name }}</span>
                    </li>
                    <li class="list-group-item d-flex">
                        <span class="col-4">E-mail:</span>
                        <span class="col-8">{{ $user->email }}</span>
                    </li>
                    <li class="list-group-item d-flex">
                        <span class="col-4">Registered:</span>
                        <span class="col-8">{{ $user->created_at->formatLocalized('%d %B %Y') }}</span>
                    </li>
                    <li class="list-group-item d-flex">
                        <span class="col-4">Rights:</span>
                        <span class="col-8">
                            @foreach ($user->rights as $right)
                                <span class="badge badge-pill badge-warning">{{ $right->name }}</span>
                            @endforeach
                        </span>
                    </li>
                    <li class="list-group-item d-flex">
                        <span class="col-4">WebID:</span>
                        <span class="col-8">
                            @if($webid)
                                <i class="text-success fa fa-check"></i>
                                {{ $webid->account_id }}
                            @else
                                <i class="fa fa-times"></i>
                                Not connected
                            @endif
                        </span>
                    </li>
                    <li class="list-group-item d-flex">
                        <span class="col-4">GitHub:</span>
                        <span class="col-8">
                            @if($github)
                                <i class="text-success fa fa-check"></i>
                                <a href="https://github.com/{{ $github->account_id }}">{{ $github->account_id }}</a>
                            @else
                                <i class="fa fa-times"></i>
                                Not connected
                            @endif
                        </span>
                    </li>
                </ul>

            </div>
        </div>
    </div>

@endsection