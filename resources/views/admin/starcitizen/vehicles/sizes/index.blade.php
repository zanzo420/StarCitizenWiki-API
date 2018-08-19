@extends('admin.layouts.default')

@section('title', __('Fahrzeuggrößen'))

@section('content')
    <div class="card">
        <h4 class="card-header">@lang('Fahrzeuggrößen')</h4>
        <div class="card-body px-0 table-responsive">
            @include('components.messages')
            @include('admin.components.translation_table')
        </div>
    </div>
@endsection