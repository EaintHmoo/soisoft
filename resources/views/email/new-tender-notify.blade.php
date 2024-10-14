<x-mail::message>
<h2 style="text-align: center;margin-bottom:1rem;">New {{ $details['type'] }} is available</h2>
<h1>{{ $details['title'] }}</h1>
<p>Start Date - {{ $details['start_date'] }}</p>
<p>End Date - {{ $details['end_date'] }}</p>
<x-mail::panel>
    <x-mail::button :url="$url" color="success">
        View {{ $details['type'] }}
    </x-mail::button>
</x-mail::panel>


Thank you,<br>
{{ config('app.name') }}
</x-mail::message>