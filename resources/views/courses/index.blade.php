@extends('layout')

@section('content')
    <h2>Kurs</h2>

    {{--
        @can('create', 'App\Course')
        <p>
            <a href="{{ route('courses.new') }}" class="btn btn-success">Nytt kurs</a>
        </p>
    @endcan
    --}}
    <ul class="list-group">
        @foreach($courses as $course)
            <li class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1"><a href="{{ route('courses.show', $course->name) }}">{{ strip_tags($course->header->text) }}</a></h5>
                    <small>Updated {{ $course->updated_at->diffForHumans() }}</small>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="https://github.com/{{ $course->repo }}">{{ $course->repo }}</a>
                        @if ($course->last_commit)
                            @ <a href="https://github.com/{{ $course->repo }}/commit/{{ $course->last_commit }}">{{ substr($course->last_commit, 0, 8) }}</a>
                        @endif
                    </div>
                    <span>
                        @if ($course->github_hook)
                            <a href="{{ route('courses.ghtest', $course->name) }}">Test hook</a>
                        @else
                            <a href="{{ route('courses.ghhook', $course->name) }}">Register hook</a>
                        @endif
                    </span>
                </div>
                @if ($course->last_event_at)
                    <div>
                        <small>
                            Last '{{ $course->last_event_type }}' event from GitHub received
                            {{ $course->last_event_at->diffForHumans() }}
                        </small>
                    </div>
                @endif
            </li>
        @endforeach
    </ul>
@endsection
