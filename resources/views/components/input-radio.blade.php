@props([
    'options' => [],
    'name' => 'radio',
    'checked' => 0,
])

<div class="flex gap-4" style="flex-wrap: wrap;">
    @foreach($options as $value => $option)
        <div class="custom-radio">
            <input type="radio" name="{{ $name }}" id="{{ $name }}_{{ $value }}" class="" value="{{ $value }}"
                   @if($value == $checked) checked="checked" @endif>
            <label for="{{ $name }}_{{ $value }}" class="radio-label"><span class="outside"><span class="inside"></span></span>{{ $option }}</label>
        </div>
    @endforeach
</div>

