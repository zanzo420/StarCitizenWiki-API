@extends('user.layouts.full_width')

@section('body--class', 'bg-dark')

{{-- Page Title --}}
@section('title', __('Lizenz Akzeptieren'))

@section('topNav--class', 'd-none')

@section('main--class', 'mt-5')

@section('content')
    @component('components.heading', [
        'class' => 'text-center mb-5',
        'route' => url('/'),
    ])@endcomponent

    <div class="card bg-dark text-light-grey">
        <h4 class="card-header text-center">@lang('Editor Lizenz akzeptieren')</h4>
        <div class="card-body">
            {{-- TODO Update Text, Link zum Wiki Artikel --}}
            @lang('Durch den Klick auf "Bestätigen" bestätigst du, dass jegliche von dir übersetzten Texte der Allgemeinheit frei zur Verfügung stehen, und du keine Rechte an diesen hast.')
            <a href="{{ config('api.wiki_url') }}/Star_Citizen_Wiki:Übersetzungsvereinbarung" class="text-light-grey d-block">@lang('Mehr Informationen') <i class="fal fa-external-link fa-sm" data-fa-transform="up-3"></i></a>
        </div>
        <div class="card-footer d-flex">
            @component('components.forms.form', [
                'action' => route('web.user.auth.logout'),
            ])
                <button class="btn btn-link text-light-grey">@lang('Abbrechen')</button>
            @endcomponent
            @component('components.forms.form', [
                'class' => 'ml-auto',
                'action' => route('web.user.license.accept'),
            ])
                <button class="btn btn-outline-success">@lang('Bestätigen')</button>
            @endcomponent
        </div>
    </div>
@endsection