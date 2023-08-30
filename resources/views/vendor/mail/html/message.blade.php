<x-mail::layout>
{{-- Header --}}
<x-slot:header>
<x-mail::header :url="config('app.url')">
@if($settings['brand_logo'])
<img src="{{ url($settings['brand_logo']) }}" alt="{{ $settings['brand_name'] }}" data-auto-embed="base64" style="max-width: 200px;" />
@else
    {{ $settings['brand_name'] }}
@endif


</x-mail::header>
</x-slot:header>

{{-- Body --}}
# Hello 👋🏽
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
<x-mail::footer>Pandatask - Project Management System</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
