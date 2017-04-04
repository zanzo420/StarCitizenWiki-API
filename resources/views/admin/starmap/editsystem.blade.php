@extends('layouts.app')
@section('title', 'Edit Starmap System')

@section('content')
    @include('layouts.heading')
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6 mx-auto">
                @include('snippets.errors')
                <form role="form" method="POST" action="{{ route('admin_starmap_systems_update') }}">
                    {{ csrf_field() }}
                    <input name="_method" type="hidden" value="PATCH">
                    <input name="id" type="hidden" value="{{ $system->id }}">
                    <div class="form-group">
                        <label for="code" aria-label="Code">Code:</label>
                        <input type="text" class="form-control" id="code" name="code" aria-labelledby="code" tabindex="1" value="{{ $system->code }}" autofocus>
                    </div>

                    <button type="submit" class="btn btn-warning my-3">Edit</button>
                    <button onclick="event.preventDefault();
                            document.getElementById('delete-form').submit();" type="submit" class="btn btn-danger my-3">Delete</button>
                </form>
                <form role="form" method="POST" id="delete-form" action="{{ route('admin_starmap_systems_delete') }}">
                    {{ csrf_field() }}
                    <input name="_method" type="hidden" value="DELETE">
                    <input name="id" type="hidden" value="{{ $system->id }}">
                </form>
            </div>
        </div>
    </div>
@endsection
