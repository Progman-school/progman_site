<x-mail::layout>
{{-- Header --}}
<x-slot:header>
<x-mail::header :url="config('app.url')">
    <h2 style="pointer-events: none; text-align: center">{{ config('app.name') }}</h2>
</x-mail::header>
</x-slot:header>

{{-- Body --}}
{{ $slot }}
{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{{ $subcopy }}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
© {{ date('Y') }} {{ config('app.name') }}. @lang("Let's program!")
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
