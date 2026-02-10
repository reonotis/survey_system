@props([
    'name' => '',
    'id' => '',
    'value' => 1,
    'label' => '',
    'data_label' => '',
    'checked' => false,
    'disabled' => false,
])

<label class="custom-checkbox">
    <input type="checkbox"
           name="{{ $name }}"
           id="{{ $id }}"
           class="checkbox-icon"
           value="{{ $value }}"
           @if($data_label) data-label="{{ $data_label }}" @endif
           @if($checked) checked="checked" @endif
           @if($disabled) disabled @endif
    >
    <span class="checkmark" ></span>

    @if($label)
        <span class="checkbox-item" for="{{ $id }}">{{ $label }}</span>
    @endif
</label>

