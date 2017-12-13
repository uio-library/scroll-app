@extends('layout')

@section('content')
    <h2>Kurs</h2>

    @can('create', 'App\Course')
        <p>
            <a href="{{ route('courses.new') }}" class="btn btn-success">Nytt kurs</a>
        </p>
    @endcan

    <ul class="list-group">
        @foreach($courses as $course)
            <li class="list-group-item d-flex justify-content-between align-items-center">

                <a href="{{ route('courses.show', $course->name) }}">{{ $course->name }}</a>

                @can('edit', $course)
                    <a href="{{ route('courses.settings', $course->name) }}" class="btn">
                        <i class="fa fa-sliders" aria-hidden="true"></i>
                        Settings
                    </a>
                @endcan

            </li>
        @endforeach
    </ul>
@endsection