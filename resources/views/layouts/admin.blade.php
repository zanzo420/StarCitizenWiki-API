<!DOCTYPE html>
<html style="overflow-y: scroll; min-height: 100%; width: 100vw; overflow-x: hidden">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Star Citizen Wiki API Admin - @yield('title')</title>
        @if ($bootstrapModules['enableCSS'])
            <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
        @endif

        <!-- Scripts -->
        <script>
            window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
        </script>
        <style>
            a.collapsed i::before {
                content: "\f0da" !important;
            }
        </style>
        @yield('header')
    </head>
    <body style="min-height: 100vh;">
        @include('admin.components.messages')
        <div class="container-fluid" style="min-height: 100vh;">
            <div class="row" style="min-height: 100vh;">
                <div class="col-12 col-md-2 bg-inverse pb-4" style="min-height: 100vh;">
                    <a href="{{ route('admin_dashboard') }}">
                        <img src="{{ asset('media/images/Star_Citizen_Wiki_Logo.png') }}" class="d-block mx-auto my-4 img-fluid" style="max-width: 100px;">
                    </a>
                    <ul class="nav flex-column">
                        <li class="nav-item ">
                            <span class="nav-link text-muted">
                                @lang('layouts/admin.app')
                            </span>
                            <ul class="nax flex-column pl-0" id="app">
                                @unless(is_null(\Illuminate\Support\Facades\Auth::user()))
                                <li class="nav-item">
                                    <a class="nav-link text-white active" href="{{ route('auth_logout') }}"><i class="fa fa-sign-out mr-1"></i> @lang('layouts/admin.logout')</a>
                                </li>
                                @endunless
                                <li class="nav-item">
                                    <a class="nav-link text-white active" href="{{ route('admin_logs') }}"><i class="fa fa-book mr-1"></i> @lang('layouts/admin.logs')</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white active" href="//{{ config('app.api_url') }}"><i class="fa fa-cogs mr-1"></i> @lang('layouts/admin.api')</a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link text-white" href="//{{ config('app.tools_url') }}"><i class="fa fa-wrench mr-1"></i> @lang('layouts/admin.tools')</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="//{{ config('app.shorturl_url') }}"><i class="fa fa-link mr-1"></i> @lang('layouts/admin.short_url')</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav flex-column mt-4">
                        <li class="nav-item ">
                            <a class="nav-link text-muted collapsed" data-toggle="collapse" href="#admin" aria-expanded="false" aria-controls="admin">@lang('layouts/admin.admin') <i class="fa fa-caret-down ml-2"></i> </a>
                            <ul class="nax flex-column collapse pl-0" id="admin">
                                <li class="nav-item">
                                    <a class="nav-link text-white active" href="{{ route('admin_dashboard') }}"><i class="fa fa-dashboard mr-1"></i> @lang('layouts/admin.dashboard')</a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link text-white" href="{{ route('admin_routes_list') }}"><i class="fa fa-random mr-1"></i> @lang('layouts/admin.routes')</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('admin_users_list') }}"><i class="fa fa-users mr-1"></i> @lang('layouts/admin.user')</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav flex-column mt-4">
                        <li class="nav-item ">
                            <a class="nav-link text-muted collapsed" data-toggle="collapse" href="#urls" aria-expanded="false" aria-controls="urls">@lang('layouts/admin.urls') <i class="fa fa-caret-down ml-2"></i> </a>
                            <ul class="nax flex-column collapse pl-0" id="urls">
                                <li class="nav-item ">
                                    <a class="nav-link text-white" href="{{ route('admin_urls_list') }}"><i class="fa fa-link mr-1"></i> @lang('layouts/admin.short_urls')</a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link text-white" href="{{ route('admin_urls_whitelist_list') }}"><i class="fa fa-list mr-1"></i> @lang('layouts/admin.whitelist')</a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link text-white" href="{{ route('admin_urls_whitelist_add_form') }}"><i class="fa fa-plus-circle mr-1"></i> @lang('layouts/admin.add_whitelist')</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav flex-column mt-4">
                        <li class="nav-item ">
                            <a class="nav-link text-muted collapsed" data-toggle="collapse" href="#starmap" aria-expanded="false" aria-controls="starmap">@lang('layouts/admin.starmap') <i class="fa fa-caret-down ml-2"></i> </a>
                            <ul class="nax flex-column collapse pl-0" id="starmap">
                                <li class="nav-item ">
                                    <a class="nav-link text-white" href="{{ route('admin_starmap_systems_list') }}"><i class="fa fa-circle-o-notch mr-1"></i> @lang('layouts/admin.systems')</a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link text-white" href="{{ route('admin_starmap_systems_add_form') }}"><i class="fa fa-plus-circle mr-1"></i> @lang('layouts/admin.add_system')</a>
                                </li>
                                <li class="nav-item ">
                                    <a href="#" class="nav-link text-white" onclick="event.preventDefault(); document.getElementById('download-starmap').submit();">
                                        <form id="download-starmap" action="{{ route('admin_starmap_systems_download') }}" method="POST" style="display: none;">
                                            <input name="_method" type="hidden" value="POST">
                                            {{ csrf_field() }}
                                        </form>
                                        <i class="fa fa-repeat mr-1"></i> @lang('layouts/admin.download_starmap')
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav flex-column mt-4">
                        <li class="nav-item ">
                            <a class="nav-link text-muted collapsed" data-toggle="collapse" href="#ships" aria-expanded="false" aria-controls="ships">@lang('layouts/admin.ships') <i class="fa fa-caret-down ml-2"></i> </a>
                            <ul class="nax flex-column collapse pl-0" id="ships">
                                <li class="nav-item ">
                                    <a class="nav-link text-white" href="{{ route('admin_ships_list') }}"><i class="fa fa-rocket mr-1"></i> @lang('layouts/admin.ships')</a>
                                </li>
                                <li class="nav-item ">
                                    <a href="#" class="nav-link text-white" onclick="event.preventDefault(); document.getElementById('download-ships').submit();">
                                        <form id="download-ships" action="{{ route('admin_ships_download') }}" method="POST" style="display: none;">
                                            <input name="_method" type="hidden" value="POST">
                                            {{ csrf_field() }}
                                        </form>
                                        <i class="fa fa-repeat mr-1"></i> @lang('layouts/admin.download_ships')
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="col-12 col-md-10" style="background: #fafafa; padding-right: 30px">
                    <h1 class="my-4 text-center">@yield('title')</h1>
                    @yield('content')
                </div>
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.js" integrity="sha256-jVfFb7AbGi7S/SLNl8SB4/MYaf549eEs+NlIWMoARHg=" crossorigin="anonymous"></script>
        <script src="{{ mix('/js/app.js') }}"></script>
        @yield('scripts')
    </body>
</html>
