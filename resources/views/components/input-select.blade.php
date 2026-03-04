@props([
    'options' => [],
    'name' => 'select',
    'id' => '',
])

<select name="{{ $name }}" id="{{ $id }}" class="input-box">
    @foreach($options as $value => $option)
        <option
            value="{{ $value }}">{{ $option }}</option>
    @endforeach
</select>
