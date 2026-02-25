@props([
    'options' => [],
    'name' => 'radio',
    'checked' => 0,
])

<div class="flex gap-4" style="flex-wrap: wrap;">

    @foreach($options as $option)
        <div class="custom-radio">
            <input type="radio" name="{{ $name }}" id="{{ $name }}_{{ $option['value'] }}" class="" value="{{ $option['value'] }}"
                   @if($option['value'] == $checked) checked="checked" @endif>
            <label for="{{ $name }}_{{ $option['value']  }}" class="radio-label"><span class="outside"><span class="inside"></span></span>{{ $option['label'] }}</label>
        </div>
    @endforeach
</div>

