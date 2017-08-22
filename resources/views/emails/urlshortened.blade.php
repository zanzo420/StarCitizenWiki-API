@component('mail::message')
# Star Citizen Wiki URL Shortened

**URL:** `{{ $url->url }}`

**Hash Name:** `{{ $url->hash }}`

**Owner:** `{{ $url->user()->first()->email }}`

<br>
{{ config('app.name') }}
@endcomponent
