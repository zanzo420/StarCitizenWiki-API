@extends('api.auth.layouts.default')

{{-- Page Title --}}
@section('title', '__LOC__ShortUrl')

@section('content')
@include('components.errors')

<div class="card">
    <h4 class="card-header">__LOC__Edit_ShortUrl</h4>
    <div class="card-body">
        @component('components.forms.form', [
            'action' => route('account_urls_update'),
            'method' => 'PATCH',
        ])
            <input name="id" type="hidden" value="{{ $url->getRouteKey() }}">
            @component('components.forms.form-group', [
                'id' => 'url',
                'label' => '__LOC__Url',
                'inputType' => 'url',
                'tabIndex' => 1,
                'autofocus' => 1,
                'value' => $url->url,
                'inputOptions' => 'spellcheck=false',
            ])@endcomponent

            @component('components.forms.form-group', [
                'id' => 'hash',
                'label' => '__LOC__Hash',
                'tabIndex' => 2,
                'value' => $url->hash,
                'inputOptions' => 'data-minlength=3 spellcheck=false',
            ])@endcomponent

            @component('components.forms.form-group', [
                'id' => 'expired_at',
                'label' => '__LOC__Expired_at',
                'inputType' => 'datetime-local',
                'tabIndex' => 3,
                'inputOptions' => 'min='.\Carbon\Carbon::now()->format("Y-m-d\TH:i"),
            ])
                @slot('value')
                    @unless(is_null($url->expired_at)){{ \Carbon\Carbon::parse($url->expired_at)->format('Y-m-d\TH:i') }}@endunless
                @endslot
            @endcomponent

            <button class="btn btn-outline-success btn-block-xs-only pull-right">__LOC__Edit</button>
        @endcomponent
    </div>
</div>
@endsection
