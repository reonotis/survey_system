@props([
    'type' => 'text',
    'name' => 'name',
    'id' => '',
    'placeholder' => '',
    'checked_list' => [],
    'error' => false,
    'class' => '',
    'value' => '',
    'required' => false,
])

@php
    if ($error) {
        $class .= ' error';
    }
@endphp

<input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}"
       {{ $attributes->merge(['class' => trim('input-box ' . $class)]) }}
       value="{{ $value }}"
       placeholder="{{ $placeholder }}"
       @if($required) required @endif
>

