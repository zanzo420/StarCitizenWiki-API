@extends('layouts.app')
@section('title', 'Star Citizen Wiki API - Edit URL')
@section('lead', 'Edit URL')

@section('content')
    @include('layouts.heading')
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6 mx-auto">
                @include('snippets.errors')
                <form role="form" method="POST" action="/admin/urls/{{ $url->id }}">
                    {{ csrf_field() }}
                    <input name="_method" type="hidden" value="PATCH">
                    <div class="form-group">
                        <label for="url" aria-label="Name">URL:</label>
                        <input type="url" class="form-control" id="url" name="url" aria-labelledby="url" tabindex="1" value="{{ $url->url }}" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="hash_name" aria-label="Name">Name:</label>
                        <input type="text" class="form-control" id="hash_name" name="hash_name" required aria-required="true" aria-labelledby="hash_name" tabindex="2" data-minlength="3" value="{{ $url->hash_name }}">
                    </div>
                    <div class="form-group">
                        <label for="user_id">Example select</label>
                        <select class="form-control" id="user_id" name="user_id">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" <?php if ($url->user_id === $user->id) { echo 'selected';} ?>>{{ $user->email }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-warning my-3">Edit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
