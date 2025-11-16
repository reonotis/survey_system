@props([
    'name' => 'checkbox[]',
    'label' => '',
    'value' => '',
    'id' => '',
    'checked' => false,
])

<div class="checkbox-content flex-start-center">
    <input type="checkbox" name="{{ $name }}" id="{{ $id }}" class="checkbox" value="{{ $value }}"
           @if($checked) checked="checked" @endif>
    <label class="checkbox-item" for="{{ $id }}">{{ $label }}</label>
</div>

