@props([
    'type' => 'text',
    'name' => 'name',
    'placeholder' => '',
    'checked_list' => [],
    'error' => false,
    'class' => '',
    'value' => '',
])

@php
    if ($error) {
        $class .= ' error';
    }
@endphp

<input type="{{ $type }}" name="{{ $name }}"
       {{ $attributes->merge(['class' => trim('input-box ' . $class)]) }}
       value="{{ $value }}"
       placeholder="{{ $placeholder }}"
>

