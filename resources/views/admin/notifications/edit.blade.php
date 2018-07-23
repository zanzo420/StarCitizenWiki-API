@extends('admin.layouts.default')

@section('content')
    <div class="card">
        <h4 class="card-header">@lang('Notification bearbeiten')</h4>
        <div class="card-body">
            @component('components.forms.form', [
                'method' => 'PATCH',
                'action' => route('web.admin.notifications.update', $notification->getRouteKey()),
            ])
                @component('components.forms.form-group', [
                    'inputType' => 'textarea',
                    'label' => __('Inhalt'),
                    'id' => 'content',
                    'rows' => 6,
                    'value' => $notification->content,
                ])@endcomponent

                <div class="row">
                    <div class="col-12 col-lg-6">
                        @component('components.forms.form-group', [
                            'inputType' => 'select',
                            'inputClass' => 'custom-select w-100',
                            'label' => __('Typ'),
                            'id' => 'level',
                        ])
                            @slot('selectOptions')
                                <option value="0" @if($notification->level == 0) selected @endif>@lang('Info')</option>
                                <option value="1" @if($notification->level == 1) selected @endif>@lang('Warnung')</option>
                                <option value="2" @if($notification->level == 2) selected @endif>@lang('Fehler')</option>
                                <option value="3" @if($notification->level == 3) selected @endif>@lang('Kritisch')</option>
                            @endslot
                        @endcomponent
                    </div>
                    <div class="col-12 col-lg-6">
                        @component('components.forms.form-group', [
                            'inputType' => 'datetime-local',
                            'label' => __('Ablaufdatum'),
                            'id' => 'expired_at',
                            'value' => $notification->expired_at->format("Y-m-d\TH:i"),
                        ])@endcomponent
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-6">
                        @component('components.forms.form-group', [
                            'inputType' => 'number',
                            'label' => __('Reihenfolge'),
                            'id' => 'order',
                            'value' => $notification->order,
                            'inputOptions' => 'min=0 max=5',
                        ])
                            <small class="help-block">@lang('Reihenfolge auf Startseite, Aufsteigend sortiert')</small>
                        @endcomponent
                    </div>
                    <div class="col-12 col-lg-6">
                        @component('components.forms.form-group', [
                            'inputType' => 'datetime-local',
                            'label' => __('Erstelldatum'),
                            'id' => 'published_at',
                            'value' => $notification->published_at->format("Y-m-d\TH:i"),
                        ])@endcomponent
                    </div>
                </div>
                <div class="form-group">
                    <span class="d-block">@lang('Ausgabetyp'):</span>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="status" name="output[]"
                               @if($notification->output_status) checked @endif>
                        <label class="custom-control-label" for="status">@lang('Statusseite')</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="index" name="output[]"
                               @if($notification->output_index) checked @endif>
                        <label class="custom-control-label" for="index">@lang('Startseite')</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="email" name="output[]"
                               @if($notification->output_email) checked disabled @endif>
                        <label class="custom-control-label" for="email">@lang('E-Mail')</label>
                    </div>
                    @if($notification->output_email)
                        <div class="custom-control custom-checkbox text-danger">
                            <input type="checkbox" class="custom-control-input" id="resend_mail" name="resend_mail"
                                   value="resend_mail">
                            <label class="custom-control-label" for="resend_mail">@lang('E-Mail erneut versenden')</label>
                        </div>
                    @endif
                </div>

                <button class="btn btn-outline-secondary" name="save">@lang('Speichern')</button>
                @if($notification->trashed())
                    <button class="btn btn-outline-success float-right" name="restore">@lang('Wiederherstellen')</button>
                @else
                    <button class="btn btn-outline-danger float-right" name="delete">@lang('Löschen')</button>
                @endif
            @endcomponent
        </div>
    </div>
@endsection
