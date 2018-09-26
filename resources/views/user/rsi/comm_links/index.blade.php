@extends('user.layouts.default_wide')

@section('title', __('Comm Links'))

@section('content')
    <div class="card">
        <h4 class="card-header">@lang('Comm Links')</h4>
        <div class="card-body px-0 table-responsive">
            <table class="table table-striped mb-0" data-order='[[ 0, "desc" ]]'>
                <thead>
                <tr>
                    @can('web.user.internals.view')
                        <th>@lang('ID')</th>
                    @endcan
                    <th>@lang('CIG ID')</th>
                    <th>@lang('Titel')</th>
                    <th>@lang('Bilder')</th>
                    <th>@lang('Links')</th>
                    <th>@lang('Inhalt')</th>
                    <th>@lang('Übersetzt')</th>
                    <th>@lang('Channel')</th>
                    <th>@lang('Kategorie')</th>
                    <th>@lang('Serie')</th>
                    <th>@lang('Veröffentlichung')</th>
                    <th data-orderable="false">&nbsp;</th>
                </tr>
                </thead>
                <tbody>

                @forelse($commLinks as $commLink)
                    <tr>
                        @can('web.user.internals.view')
                            <td>
                                {{ $commLink->id }}
                            </td>
                        @endcan
                        <td>
                            <a href="{{ $commLink->url ?? "https://robertsspaceindustries.com/comm-link/SCW/{$commLink->cig_id}-API" }}" target="_blank">{{ $commLink->cig_id }}</a>
                        </td>
                        <td>
                            {{ $commLink->title }}
                        </td>
                        <td>
                            {{ count($commLink->images) }}
                        </td>
                        <td>
                            {{ count($commLink->links) }}
                        </td>
                        <td>
                            {{ $commLink->english()->translation ? 'Ja' : 'Nein' }}
                        </td>
                        <td class="text-{{ optional($commLink->german())->translation ? 'success' : 'danger' }}">
                            {{ optional($commLink->german())->translation ? 'Ja' : 'Nein' }}
                        </td>
                        <td>
                            {{ $commLink->channel->name }}
                        </td>
                        <td>
                            {{ $commLink->category->name }}
                        </td>
                        <td>
                            {{ $commLink->series->name }}
                        </td>
                        <td title="{{ $commLink->created_at->format('d.m.Y') }}" data-search="{{ $commLink->created_at->format('d.m.Y') }}">
                            {{ $commLink->created_at->diffForHumans() }}
                        </td>
                        <td class="text-center">
                            @component('components.edit_delete_block')
                                @slot('show_url')
                                    {{ route('web.user.rsi.comm-links.show', $commLink->getRouteKey()) }}
                                @endslot
                                @can('web.user.rsi.comm-links.update')
                                    @slot('edit_url')
                                        {{ route('web.user.rsi.comm-links.edit', $commLink->getRouteKey()) }}
                                    @endslot
                                @endcan
                                {{ $commLink->getRouteKey() }}
                            @endcomponent
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12">@lang('Keine Comm Links vorhanden')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $commLinks->links() }}</div>
    </div>
@endsection

@section('body__after')
    @parent
    @if(count($commLinks) > 0)
        @include('components.init_dataTables')
    @endunless
@endsection