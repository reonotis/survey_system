@props([
    'options' => [],
    'name' => 'checkbox',
    'checked_list' => [],
])

<div class="flex gap-1" style="flex-wrap: wrap;">
    @foreach($options as $value => $option)
        <x-input-checkbox
            name="{{ $name }}[]"
            label="{{ $option }}"
            id="{{ $name }}_{{ $value }}"
            value="{{ $value }}"
            :checked="in_array($value, $checked_list)"
        />
    @endforeach
</div>

