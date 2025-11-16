@props([
    'options' => [],
    'name' => 'radio',
    'checked' => 0,
])

<div class="flex gap-2" style="flex-wrap: wrap;">
    @foreach($options as $value => $option)
        <div>
            <input type="radio" name="{{ $name }}" id="{{ $name }}_{{ $value }}" class="" value="{{ $value }}"
                   @if($value == $checked) checked="checked" @endif>
            <label for="{{ $name }}_{{ $value }}">{{ $option }}</label>
        </div>
    @endforeach
</div>

