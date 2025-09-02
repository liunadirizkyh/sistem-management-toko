{{-- File: resources/views/components/application-logo.blade.php --}}

@props([
    'height' => 'h-36',
    'width' => 'w-auto',
])

<img src="{{ asset('images/logo.png') }}" alt="Logo Aplikasi" {{ $attributes->merge(['class' => "$height $width"]) }}>