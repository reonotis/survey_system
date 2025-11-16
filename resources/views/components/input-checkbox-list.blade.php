@props([
    'options' => [],
    'name' => 'checkbox',
    'checked_list' => [],
])

<div class="flex gap-2" style="flex-wrap: wrap;">
    @foreach($options as $value => $option)
        <div>
            <input type="checkbox" name="{{ $name }}[]" id="{{ $name }}_{{ $value }}" class="" value="{{ $value }}"
                   @if(in_array($value, $checked_list)) checked="checked" @endif>
            <label for="{{ $name }}_{{ $value }}">{{ $option }}</label>
        </div>
    @endforeach
</div>

