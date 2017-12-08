@extends('layout')

@section('content')
    <h2>Kurs</h2>

    <form action="{{ route('courses.settings.save', $course->name) }}">
        <div class="form-group">
            <label for="repo">GitHub repo</label>
            <input type="text" class="form-control" id="repo" aria-describedby="repoHelp">
            <small id="repoHelp" class="form-text text-muted">p</small>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection