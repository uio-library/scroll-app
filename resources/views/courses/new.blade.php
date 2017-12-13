@extends('layout')

@section('content')
    <div class="card">
        <div class="card-header">Nytt kurs</div>

        <div class="card-body">
            <form class="form-horizontal" method="POST" action="{{ route('courses.new.save') }}">
                {{ csrf_field() }}

                <div class="row form-group{{ $errors->has('repo') ? ' has-error' : '' }}">
                    <label for="repo" class="col-md-2 control-label">Repo</label>

                    <div class="col-md-6">
                        <input id="repo" type="text" class="form-control" name="repo" placeholder="username/repo" value="{{ old('repo') }}" required autofocus>

                        @if ($errors->has('repo'))
                            <span class="help-block">
                            <strong>{{ $errors->first('repo') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-6 offset-md-2">
                        <button type="submit" class="btn btn-primary">
                            Create
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection