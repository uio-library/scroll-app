@extends('layout')

@section('content')

    <h2>Something went wrong</h2>

    <p>
        {{ $exception->getMessage() }}
    </p>

@endsection