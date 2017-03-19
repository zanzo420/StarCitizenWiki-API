<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/x-icon" href="{{ URL::asset('/media/images/rsi_im/favicon.ico') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>RSI.im - Star Citizen Wiki Short URL Service</title>
        <link rel="stylesheet" href="{{ URL::asset('/css/app.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('/css/rsi_im/app.css') }}">
    </head>
    <body>
        <main class="container" id="form">
            <div class="row justify-content-center" style="height: 100vh">
                <div class="col-10 col-md-6 align-self-center d-flex form-container">
                    <div class="w-100">
                        <img src="{{ URL::asset('/media/images/rsi_im/logo.png') }}" class="img-responsive mb-5">
                        @include('snippets.errors')
                        @if (session('hash_name'))
                            <div class="alert alert-success text-center">
                                https://{{config('app.shorturl_url')}}/{{ session('hash_name') }}
                            </div>
                        @endif
                        <form id="shorten-form" class="w-100" role="form" method="POST" action="{{ route('shorten') }}">
                            {{ csrf_field() }}
                            <div class="input-group input-group-lg mb-2">
                                <input type="url" name="url" id="url" class="form-control" placeholder="Long URL" required>
                                <span class="input-group-btn">
                                    <button class="btn btn-info" type="submit">Shorten</button>
                                </span>
                                <span class="input-group-btn">
                                    <button class="btn btn-secondary" type="button" data-toggle="collapse" href="#customize" aria-expanded="false" aria-controls="customize"><i class="fa fa-cog"></i></button>
                                </span>
                            </div>
                            <div class="collapse mt-3" id="customize">
                                <div class="input-group">
                                    <span class="input-group-addon" id="hash_name-label">Custom Name:</span>
                                    <input type="text" class="form-control" id="hash_name" name="hash_name" aria-describedby="hash_name-label" placeholder="Alphanumeric and -_">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-12 d-flex fixed-bottom">
                    <ul class="nav justify-content-end w-100">
                        <li class="nav-item">
                            <a class="nav-link text-info" href="#whitelist-modal" data-toggle="modal" data-target="#whitelist-modal">Whitelist</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-info" href="https://{{ config('app.api_url') }}">API</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-info" href="https://star-citizen.wiki/Star_Citizen_Wiki:Impressum">Legal</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="modal fade" id="whitelist-modal" tabindex="-1" role="dialog" aria-labelledby="whitelist-modal-label" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="whitelist-modal-label">URL Whitelist</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <ul>
                                @foreach($whitelistedURLs as $whitelistedURL)
                                    <li>{{ $whitelistedURL->url }}</li>
                                @endforeach
                            </ul>
                            <hr>
                            <a href="mailto:api@star-citizen.wiki?subject=RSI.IM URL Whitelist Request&body=Whitelist Request for the following Domain(s):">Add URL to Whitelist</a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <script>window.Tether = function () {};</script>
        <script src="{{ URL::asset('/js/app.js') }}"></script>
    </body>
</html>